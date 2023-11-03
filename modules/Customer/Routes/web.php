<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\CustomerController;

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

Route::prefix('admin/customer')->as('admin.customer.')->group(function () {
    Route::resource('/', CustomerController::class)->parameter('', 'customer');
    Route::post('{customer}/status-update', [CustomerController::class, 'statusUpdate'])->name('status-update');
});
