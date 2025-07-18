<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\InfoControlller;
use App\Http\Controllers\NgrokStaticController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WifiClientController;
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
    Route::get('getMonitor', [MonitorController::class, 'getMonitor']);

    Route::get('printPreview', [PrintController::class, 'printPreview'])->name('printPreview');
    Route::get('print', [PrintController::class, 'print'])->name('print');
    Route::get('comment', [PrintController::class, 'comment'])->name('comment');
    Route::post('insertComment', [PrintController::class, 'insertComment'])->name('insertComment');

    Route::get('graphPreview', [GraphController::class, 'graphPreview'])->name('graphPreview');
    Route::get('graph', [GraphController::class, 'graph'])->name('graph');
});

Route::middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::resource('users', UserController::class);

    Route::get('profil', [ProfilController::class, 'index'])->name('profilView');
    Route::post('profil/update', [ProfilController::class, 'profil'])->name('profil.update');

    Route::get('info', [InfoControlller::class, 'index'])->name('info');

    Route::get('wifiClient', [WifiClientController::class, 'index'])->name('wifiClientView');
    Route::post('wifiClient/update', [WifiClientController::class, 'wifiClient'])->name('wifiClient.update');

    Route::get('ngrokStatic', [NgrokStaticController::class, 'index'])->name('ngrokStaticView');
    Route::post('ngrokStatic/update', [NgrokStaticController::class, 'ngrokStatic'])->name('ngrokStatic.update');

    Route::get('config', [ConfigController::class, 'index'])->name('config.index');
    Route::post('config/update', [ConfigController::class, 'updateField'])->name('config.update');
});