<?php

use Illuminate\Support\Facades\Route;
use Modules\Pwa\Http\Controllers\PWAController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['as' => 'pwa.', 'prefix' => 'pwa'], function () {
    Route::get('manifest.json', [PWAController::class, 'manifest'])
        ->name('manifest.json');
    Route::get('offline/', [PWAController::class, 'offline'])->name('offline');
    Route::get('init.js', [PWAController::class, 'initJs'])
        ->name('init.js');
    Route::get('service-worker.js', [PWAController::class, 'serviceWorkerJs'])
        ->name('service-worker.js');
});
