<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('monitor');
    } else {
        return redirect('/login');
    }
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('monitor', [MonitorController::class, 'showMonitor'])->name('monitor');
    Route::get('/getMonitor', [MonitorController::class, 'getMonitor']);

    Route::post('print', [PrintController::class, 'print'])->name('print');
    Route::get('print', [PrintController::class, 'showPrint'])->name('print');

    Route::post('graph', [PrintController::class, 'graph'])->name('grpah');
    Route::get('graph', [PrintController::class, 'showGraph'])->name('graph');
});

Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('users', UserController::class);
});