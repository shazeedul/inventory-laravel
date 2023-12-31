<?php

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\InvoiceController;

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

Route::prefix('admin/invoice')->as('admin.invoice.')->group(function () {
    Route::resource('/', InvoiceController::class)->parameter('', 'invoice');
    Route::get('{invoice}/approve', [InvoiceController::class, 'approveList'])->name('approve.list');
    Route::post('{invoice}/approve', [InvoiceController::class, 'approve'])->name('approve');
});
