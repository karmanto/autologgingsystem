<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfigController extends Controller
{
    private $settings;

    public function __construct()
    {
        $this->settings = json_decode(Storage::get('settings.json'), true);
    }

    public function index()
    {
        return view('config', ['settings' => $this->settings]);
    }

    public function updateField(Request $request)
    {
        $request->validate([
            'nickname' => 'required|max:7',
            'fullname' => 'required|max:25',
        ], [
            'nickname.max' => 'Nickname cannot be more than 7 characters.',
            'fullname.max' => 'Fullname cannot be more than 25 characters.',
        ]);

        $fieldIndex = array_search($request->field, array_column($this->settings['show_list'], 'field'));

        if ($fieldIndex !== false) {
            $this->settings['show_list'][$fieldIndex]['stat'] = (bool) $request->stat;
            $nameIndex = array_search($request->field, array_column($this->settings['name_list'], 'field'));
            if ($nameIndex !== false) {
                $this->settings['name_list'][$nameIndex]['nickname'] = $request->nickname;
                $this->settings['name_list'][$nameIndex]['fullname'] = $request->fullname;
            }
            Storage::put('settings.json', json_encode($this->settings, JSON_PRETTY_PRINT));
            return redirect()->back()->with('success', 'Field updated successfully.');
        }

        return redirect()->back()->with('error', 'Field not found.');
    }
}
