<?php

use Illuminate\Support\Facades\Route;
use Modules\Unit\Http\Controllers\UnitController;

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

Route::prefix('admin/unit')->as('admin.unit.')->group(function () {
    Route::resource('/', UnitController::class)->parameter('', 'unit');
    Route::post('{unit}/status-update', [UnitController::class, 'statusUpdate'])->name('status-update');
});
