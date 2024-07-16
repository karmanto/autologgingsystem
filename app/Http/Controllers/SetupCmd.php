<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SetupCmd extends Controller
{
    private $cmd_file;

    public function __construct()
    {
        $this->cmd_file = json_decode(Storage::get('setup_cmd.json'), true);
    }
    
    public function index()
    {
        return view('cmdView');
    }

    public function cmdSetup(Request $request)
    {
        $request->validate([
            'pwd' => 'required|integer'
        ]);

        $pwd = $request->input('pwd');

        if ($pwd == 93) {
            $this->cmd_file['setup_cmd'] = false;
            Storage::put('setup_cmd.json', json_encode($this->cmd_file));
            return redirect()->back()->with('success', 'Setup cmd started!');
        } 

        return redirect()->back()->with('error', 'Failed: Incorrect password.');
    }
}
