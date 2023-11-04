<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\PurchaseController;

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

Route::prefix('admin/purchase')->as('admin.purchase.')->group(function () {
    Route::resource('/', PurchaseController::class)->parameter('', 'purchase');
    Route::post('{purchase}/status-update', [PurchaseController::class, 'statusUpdate'])->name('status-update');
});
