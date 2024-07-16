<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DataMonitor;

class GraphController extends Controller
{
    private $settings;
    private $graphListFields;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
        $this->graphListFields = array_column(array_filter($this->settings['show_list'], function($field) {
            return $field['stat'] === true;
        }), 'field');
    }
    
    public function graph()
    {
        return view('graph', ['settings' => $this->settings]);
    }

    public function graphPreview(Request $request)
    {
        $request->validate([
            'set_date' => 'required|date',
        ]);

        $setDate = $request->input('set_date');
        $graphStartHour = str_pad($this->settings['print_start_hour'], 2, "0", STR_PAD_LEFT);
        $startDateTime = "$setDate $graphStartHour:00:00";
        $endDateTime = date('Y-m-d H:i:s', strtotime("$startDateTime +1 day"));
        $fieldsToSelect = array_merge(['created_at'], $this->graphListFields);

        $results = DataMonitor::select($fieldsToSelect)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->get();

        $changeLogs = $this->generateChangeLogs($results->toArray());

        return view('graphShow', [
            'changeLogs' => $changeLogs,
            'settings' => $this->settings,
        ]);
    }

    private function generateChangeLogs(array $results)
    {
        $changeLogs = [];

        foreach ($results as $result) {
            foreach ($this->graphListFields as $field) {
                if (!isset($result[$field])) continue;

                $value = $result[$field];

                $changeLogs[$field][] = $this->createChangeLog($value, $result['created_at']);
            }
        }

        return $changeLogs;
    }

    private function createChangeLog($value, $createdAt)
    {
        return [
            'value' => $value,
            'created_at' => $createdAt
        ];
    }
}
