<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NgrokStaticController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
    }
    
    public function index()
    {
        return view('ngrokStatic', ['settings' => $this->settings]);
    }

    public function ngrokStatic(Request $request)
    {
        if (!$request->has('ngrokStatic') || empty($request->ngrokStatic)) {
            return redirect()->back()->with('error', 'Request tidak valid atau kosong.');
        }

        $this->settings['ngrok_static'] = $request->ngrokStatic;
        Storage::put('settings.json', json_encode($this->settings));

        $flagFilePath = storage_path('app/ngrok_static.txt');
        if (!file_exists($flagFilePath)) {
            Storage::put('ngrok_static.txt', '');
        }
        if (file_put_contents($flagFilePath, "{$request->ngrokStatic}\n1") !== false) {
            return redirect()->back()->with('success', 'NGROK Static Domain berhasil diperbarui! Sistem Restart');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah file ngrok_static.txt.');
        }
    }
}
