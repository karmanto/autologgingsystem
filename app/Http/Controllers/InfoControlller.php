<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class InfoControlller extends Controller
{
    private $info;
    private $settings;

    public function __construct()
    {
        $this->info = json_decode(Storage::get('info.json'), true);
        $this->settings = json_decode(Storage::get('settings.json'), true);
    }
    
    public function index()
    {
        return view('info', [
            'info' => $this->info, 
            'settings' => $this->settings,
        ]);
    }
}
