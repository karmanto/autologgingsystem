<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
    }
    
    public function index()
    {
        return view('profil', ['settings' => $this->settings]);
    }

    public function profil(Request $request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'hour' => 'required|numeric|min:0|max:23',
        ]);

        $this->settings['pt_name'] = $request->name;
        $this->settings['print_start_hour'] = $request->hour;

        Storage::put('settings.json', json_encode($this->settings));

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
