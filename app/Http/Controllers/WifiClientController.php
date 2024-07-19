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
            'psk' => 'nullable|string|max:63',
        ]);

        $this->settings['wifi_client']['ssid'] = $request->ssid;
        $this->settings['wifi_client']['psk'] = $request->psk;

        Storage::put('settings.json', json_encode($this->settings, JSON_PRETTY_PRINT));

        $wpaSupplicantContent = "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev\n";
        $wpaSupplicantContent .= "update_config=1\n\n";
        $wpaSupplicantContent .= "network={\n";
        $wpaSupplicantContent .= "    ssid=\"{$request->ssid}\"\n";
        if (!empty($request->psk)) {
            $wpaSupplicantContent .= "    psk=\"{$request->psk}\"\n";
        } else {
            $wpaSupplicantContent .= "    key_mgmt=NONE\n";
        }
        $wpaSupplicantContent .= "}\n";

        $wpaSupplicantFilePath = storage_path('app/wpa_supplicant.conf');
        if (Storage::put('wpa_supplicant.conf', $wpaSupplicantContent)) {
            return redirect()->back()->with('success', 'Wi-Fi Client berhasil diperbarui! Perbarui pengaturan Wi-Fi. Sistem Restart');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah file wpa_supplicant.conf.');
        }
    }
}
