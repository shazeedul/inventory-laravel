<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\Http\Controllers\FinancialYearController;
use Modules\Account\Http\Controllers\AccountSubCodeController;
use Modules\Account\Http\Controllers\AccountPredefineController;

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

Route::prefix('admin/account')->as('admin.account.')->group(function () {
    Route::name('financial-year.')->prefix('financial-year')->group(function () {
        Route::resource('/', FinancialYearController::class)->parameter('', 'financialYear')->except('show');
        Route::get('/close', [FinancialYearController::class, 'close'])->name('close');
        Route::post('/close', [FinancialYearController::class, 'closeStore'])->name('close.store');
    });
    Route::name('sub_code.')->prefix('sub-code')->group(function () {
        Route::resource('/', AccountSubCodeController::class)->parameter('', 'subCode')->only('index');
    });
    Route::get('/predefine', [AccountPredefineController::class, 'index'])->name('predefine.index');
    Route::post('/predefine', [AccountPredefineController::class, 'store'])->name('predefine.store');
});
