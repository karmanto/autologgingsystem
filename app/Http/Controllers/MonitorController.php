<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class MonitorController extends Controller
{
    public function showMonitor()
    {
        $settingsJson = Storage::get('settings.json');
        $settings = json_decode($settingsJson, true);

        return view('monitor', compact('settings'));
    }

    public function getMonitor(): JsonResponse
    {
        $monitorJson = Storage::get('monitor.json');
        $monitor = json_decode($monitorJson, true);

        return response()->json($monitor);
    }
}
