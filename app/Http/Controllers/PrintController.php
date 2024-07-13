<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PrintController extends Controller
{
    private $settings;
    private $printListFields;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
        $this->printListFields = array_column(array_filter($this->settings['print_list'], function($field) {
            return $field['stat'] === true;
        }), 'field');
    }
    
    public function showPrint()
    {
        return view('print', ['settings' => $this->settings]);
    }

    public function print(Request $request)
    {
        $request->validate([
            'set_date' => 'required|date',
            'is_proc_calc' => 'required|in:0,1',
            'is_decimal' => 'required|in:0,1',
        ]);

        $setDate = $request->input('set_date');
        $printStartHour = $this->settings['print_start_hour'];

        $startDateTime = "$setDate $printStartHour:00:00";
        $endDateTime = date('Y-m-d H:i:s', strtotime("$startDateTime +1 day"));

        $fieldsToSelect = array_merge(['created_at'], $this->printListFields);

        $results = DB::table('data_monitor')
                    ->select($fieldsToSelect)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->get();

        $changeLogs = $this->generateChangeLogs($results->toArray());

        return view('printShow', ['changeLogs' => $changeLogs]);
    }

    private function generateChangeLogs(array $results)
    {
        $changeLogs = [];
        $previousValues = [];
        $lastResults = end($results);

        foreach ($results as $result) {
            foreach ($this->printListFields as $field) {
                if (!isset($result->$field)) continue;

                $value = $result->$field;

                if (!isset($changeLogs[$field])) {
                    $changeLogs[$field] = [];
                    if ($value == 1) {
                        $changeLogs[$field][] = $this->createChangeLog($value, $result->created_at, 'Changed to 1');
                    }
                }

                if (isset($previousValues[$field]) && $previousValues[$field] != $value) {
                    $changeLogs[$field][] = $this->createChangeLog($value, $result->created_at, $value == 1 ? 'Changed to 1' : 'Changed to 0');
                }

                if ($result == $lastResults && $value == 1) {
                    $changeLogs[$field][] = $this->createChangeLog(0, $result->created_at, 'Changed to 0');
                }

                $previousValues[$field] = $value;
            }
        }

        return $changeLogs;
    }

    private function createChangeLog($value, $changedAt, $changeType)
    {
        return [
            'value' => $value,
            'changed_at' => $changedAt,
            'change_type' => $changeType,
        ];
    }
}
