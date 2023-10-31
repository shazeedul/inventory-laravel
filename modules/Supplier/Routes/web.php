<?php

use Illuminate\Support\Facades\Route;
use Modules\Supplier\Http\Controllers\SupplierController;

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

Route::prefix('admin/supplier')->as('admin.supplier.')->group(function () {
    Route::resource('/', SupplierController::class)->parameter('', 'supplier');
    Route::post('{supplier}/status-update', [SupplierController::class, 'statusUpdate'])->name('status-update');
});
