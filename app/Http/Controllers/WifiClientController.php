<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WifiClientController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
    }
    
    public function index()
    {
        return view('wifiClient', ['settings' => $this->settings]);
    }

    public function wifiClient(Request $request)
    {
        $request->validate([
            'ssid' => 'required|string|max:32',
            'psk' => 'string|max:63',
        ]);
        
        $this->settings['wifi_client']['ssid'] = $request->ssid;
        $this->settings['wifi_client']['psk'] = $request->psk;

        Storage::put('settings.json', json_encode($this->settings, JSON_PRETTY_PRINT));
        
        $flagFilePath = storage_path('app/wifi_client_flag.txt');
        if (!file_exists($flagFilePath)) {
            Storage::put('wifi_client_flag.txt', '');
        }

        if (file_put_contents($flagFilePath, "{$request->ssid}\n{$request->psk}") !== false) {
            return redirect()->back()->with('success', 'Wifi Client berhasil diperbarui! Perbarui pengaturan Wi-Fi. Sistem Restart');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah file wifi_client_flag.txt.');
        }
    }
}
