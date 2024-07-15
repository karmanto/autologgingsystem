<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DateTime;
use App\Models\Issue;
use App\Models\DataMonitor;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    private $settings;
    private $printListFields;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
        $this->printListFields = array_column(array_filter($this->settings['show_list'], function($field) {
            return $field['stat'] === true;
        }), 'field');
    }
    
    public function print()
    {
        return view('print', ['settings' => $this->settings]);
    }

    public function printPreview(Request $request)
    {
        $request->validate([
            'set_date' => 'required|date',
        ]);

        $setDate = $request->input('set_date');
        $printStartHour = str_pad($this->settings['print_start_hour'], 2, "0", STR_PAD_LEFT);
        $startDateTime = "$setDate $printStartHour:00:00";
        $endDateTime = date('Y-m-d H:i:s', strtotime("$startDateTime +1 day"));
        $fieldsToSelect = array_merge(['created_at'], $this->printListFields);

        $results = DataMonitor::select($fieldsToSelect)
                    ->whereBetween('created_at', [$startDateTime, $endDateTime])
                    ->get();

        $allLogs = $this->generateChangeLogs($results->toArray());

        $startDateTimeObj = new DateTime($startDateTime);
        $formattedStartDateTime = $startDateTimeObj->format('d-m-y H:i');

        $endDateTimeObj = new DateTime($endDateTime);
        $formattedEndDateTime = $endDateTimeObj->format('d-m-y H:i');

        $result = Issue::whereDate('process_date', $setDate)
            ->orderBy('rev', 'desc')
            ->first();

        return view('printShow', [
            'allLogs' => $allLogs,
            'settings' => $this->settings,
            'startDate' => $formattedStartDateTime,
            'endDate' => $formattedEndDateTime,
            'set_date' => $setDate,
            'comment' => $result,
        ]);
    }

    public function comment(Request $request)
    {
        $set_date = $request->input('set_date');

        $result = Issue::whereDate('process_date', $set_date)
            ->orderBy('rev', 'desc')
            ->first();
        
        if ($result) {
            $rev = $result->rev;
        } else {
            $rev = 0;
        }
        
        return view('comment', [
            'ptName' => $this->settings['pt_name'],
            'set_date' => $set_date, 
            'rev' => $rev,
        ]);
    }

    public function insertComment(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:500|regex:/^[^\'"]*$/',
        ]);

        $comment = $request->input('comment');
        $rev = $request->input('rev');
        $process_date = $request->input('process_date');

        Issue::create([
            'comment' => $comment,
            'rev' => $rev,
            'process_date' => $process_date,
            'by' => Auth::user()->name,
        ]);

        return redirect()->route('printPreview', ['set_date' => $process_date])->with('success', 'Comment inserted successfully!');

    }

    private function generateChangeLogs(array $results)
    {
        $changeLogs = [];
        $previousValues = [];
        $lastResults = end($results);
        $runHourLogs = [];
        $allLogs = [];

        foreach ($results as $result) {
            foreach ($this->printListFields as $field) {
                if (!isset($result[$field])) continue;

                $value = $result[$field];

                if (!isset($changeLogs[$field])) {
                    $changeLogs[$field] = [];
                    if ($value == 1) {
                        $changeLogs[$field][] = $this->createChangeLog($value, $result['created_at']);
                    }
                }

                if (isset($previousValues[$field]) && $previousValues[$field] != $value) {
                    $changeLogEntry = $this->createChangeLog($value, $result['created_at']);
                    if ($value == 0 && !empty($changeLogs[$field])) {
                        $lastChangeLog = end($changeLogs[$field]);
                        if (isset($runHourLogs[$field])) {
                            $runHourLogs[$field] += $this->calculateRunHour($lastChangeLog['changed_at'], $result['created_at']);
                        } else {
                            $runHourLogs[$field] = $this->calculateRunHour($lastChangeLog['changed_at'], $result['created_at']);
                        }
                    }
                    $changeLogs[$field][] = $changeLogEntry;
                }

                if ($result == $lastResults && $value == 1) {
                    $changeLogEntry = $this->createChangeLog(0, $result['created_at']);
                    $lastChangeLog = end($changeLogs[$field]);
                    if (isset($runHourLogs[$field])) {
                        $runHourLogs[$field] += $this->calculateRunHour($lastChangeLog['changed_at'], $result['created_at']);
                    } else {
                        $runHourLogs[$field] = $this->calculateRunHour($lastChangeLog['changed_at'], $result['created_at']);
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
