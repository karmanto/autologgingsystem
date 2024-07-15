<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DateTime;

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
    
    public function showGraph()
    {
        return view('graph', ['settings' => $this->settings]);
    }

    public function graph(Request $request)
    {
        $request->validate([
            'set_date' => 'required|date',
        ]);

        $setDate = $request->input('set_date');
        $graphStartHour = str_pad($this->settings['graph_start_hour'], 2, "0", STR_PAD_LEFT);
        $startDateTime = "$setDate $graphStartHour:00:00";
        $endDateTime = date('Y-m-d H:i:s', strtotime("$startDateTime +1 day"));
        $fieldsToSelect = array_merge(['created_at'], $this->graphListFields);

        $results = DB::table('data_monitor')
                    ->select($fieldsToSelect)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->get();

        $allLogs = $this->generateChangeLogs($results->toArray());

        $startDateTimeObj = new DateTime($startDateTime);
        $formattedStartDateTime = $startDateTimeObj->format('d-m-y H:i');

        $endDateTimeObj = new DateTime($endDateTime);
        $formattedEndDateTime = $endDateTimeObj->format('d-m-y H:i');

        return view('graphShow', [
            'allLogs' => $allLogs,
            'settings' => $this->settings,
            'startDate' => $formattedStartDateTime,
            'endDate' => $formattedEndDateTime,

        ]);
    }

    private function generateChangeLogs(array $results)
    {
        $changeLogs = [];
        $previousValues = [];
        $lastResults = end($results);
        $runHourLogs = [];
        $allLogs = [];

        foreach ($results as $result) {
            foreach ($this->graphListFields as $field) {
                if (!isset($result->$field)) continue;

                $value = $result->$field;

                if (!isset($changeLogs[$field])) {
                    $changeLogs[$field] = [];
                    if ($value == 1) {
                        $changeLogs[$field][] = $this->createChangeLog($value, $result->created_at);
                    }
                }

                if (isset($previousValues[$field]) && $previousValues[$field] != $value) {
                    $changeLogEntry = $this->createChangeLog($value, $result->created_at);
                    if ($value == 0 && !empty($changeLogs[$field])) {
                        $lastChangeLog = end($changeLogs[$field]);
                        if (isset($runHourLogs[$field])) {
                            $runHourLogs[$field] += $this->calculateRunHour($lastChangeLog['changed_at'], $result->created_at);
                        } else {
                            $runHourLogs[$field] = $this->calculateRunHour($lastChangeLog['changed_at'], $result->created_at);
                        }
                    }
                    $changeLogs[$field][] = $changeLogEntry;
                }

                if ($result == $lastResults && $value == 1) {
                    $changeLogEntry = $this->createChangeLog(0, $result->created_at);
                    $lastChangeLog = end($changeLogs[$field]);
                    if (isset($runHourLogs[$field])) {
                        $runHourLogs[$field] += $this->calculateRunHour($lastChangeLog['changed_at'], $result->created_at);
                    } else {
                        $runHourLogs[$field] = $this->calculateRunHour($lastChangeLog['changed_at'], $result->created_at);
                    }
                    $changeLogs[$field][] = $changeLogEntry;
                }

                $previousValues[$field] = $value;
            }
        }

        foreach ($changeLogs as $index => $item) {
            if (!isset($allLogs[$index])) {
                $allLogs[$index] = ['data' => $item, 'runHour' => 0];
            }
        }

        foreach ($runHourLogs as $index => $item) {
            if (isset($allLogs[$index]['runHour'])) {
                $allLogs[$index]['runHour'] = $item;
            }
        }
        

        return $allLogs;
    }

    private function createChangeLog($value, $changedAt)
    {
        return [
            'value' => $value,
            'changed_at' => $changedAt
        ];
    }

    private function calculateRunHour($start, $end)
    {
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $hours = ($endTimestamp - $startTimestamp) / 3600;
        return round($hours, 2);
    }
}
