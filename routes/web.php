<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/home');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('monitor', [MonitorController::class, 'showMonitor'])->name('monitor');
Route::get('/getMonitor', [MonitorController::class, 'getMonitor']);

Route::post('print', [PrintController::class, 'print'])->name('print');
Route::get('print', [PrintController::class, 'showPrint'])->name('print');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

