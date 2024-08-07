<?php

use Illuminate\Support\Facades\Route;
use Modules\Account\Http\Controllers\DebitVoucherController;
use Modules\Account\Http\Controllers\ContraVoucherController;
use Modules\Account\Http\Controllers\CreditVoucherController;
use Modules\Account\Http\Controllers\FinancialYearController;
use Modules\Account\Http\Controllers\AccountSubCodeController;
use Modules\Account\Http\Controllers\ChartOfAccountController;
use Modules\Account\Http\Controllers\JournalVoucherController;
use Modules\Account\Http\Controllers\OpeningBalanceController;
use Modules\Account\Http\Controllers\AccountPredefineController;
use Modules\Account\Http\Controllers\AccountReportController;
use Modules\Account\Http\Controllers\AccountTransactionController;

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
        Route::post('/sub_code/subType', [AccountSubCodeController::class, 'getSubCodeBySubType'])->name('getSubCodesBySubType');
    });
    Route::get('/predefine', [AccountPredefineController::class, 'index'])->name('predefine.index');
    Route::post('/predefine', [AccountPredefineController::class, 'store'])->name('predefine.store');
    Route::prefix('coa')->name('coa.')->group(function () {
        Route::get('/', [ChartOfAccountController::class, 'index'])->name('index');
        Route::post('/', [ChartOfAccountController::class, 'store'])->name('store');
        Route::get('/edit/{chartOfAccount}', [ChartOfAccountController::class, 'edit'])->name('edit');
        Route::get('/show/{chartOfAccount}', [ChartOfAccountController::class, 'show'])->name('show');
        Route::post('/update', [ChartOfAccountController::class, 'update'])->name('update');
        Route::delete('/destroy', [ChartOfAccountController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('opening-balance')->name('opening.balance.')->group(function () {
        Route::resource('/', OpeningBalanceController::class)->parameter('', 'openingBalance')->except('show');
    });

    Route::prefix('voucher')->as('voucher.')->group(function () {
        Route::resource('/debit', DebitVoucherController::class);
        Route::resource('/credit', CreditVoucherController::class);
        Route::resource('/contra', ContraVoucherController::class);
        Route::resource('/journal', JournalVoucherController::class);
    });

    Route::controller(AccountTransactionController::class)
        ->prefix('/transaction')
        ->as('transaction.')
        ->group(function () {
            Route::get('/',  'index')->name('index');
            Route::post('/',  'approve')->name('approve');
            Route::post('/restore/{voucher}',  'restore')->name('restore');
        });

    Route::as('report.')->prefix('report')->group(function () {
        Route::get('/cash-book', [AccountReportController::class, 'cashBook'])->name('cash-book');
        Route::get('/bank-book', [AccountReportController::class, 'bankBook'])->name('bank-book');
        Route::get('/day-book', [AccountReportController::class, 'dayBook'])->name('day-book');
        Route::get('/general-ledger', [AccountReportController::class, 'generalLedger'])->name('general-ledger');
        Route::get('/sub-ledger', [AccountReportController::class, 'subLedger'])->name('sub-ledger');
        Route::get('/control-ledger', [AccountReportController::class, 'controlLedger'])->name('control-ledger');
        Route::get('/note-ledger', [AccountReportController::class, 'noteLedger'])->name('note-ledger');
        Route::get('/receive-payment', [AccountReportController::class, 'receivePayment'])->name('receive-payment');
        Route::post('/receive-payment', [AccountReportController::class, 'receivePaymentResult']);
        Route::get('/trail-balance', [AccountReportController::class, 'trailBalance'])->name('trail-balance');
        Route::post('/trail-balance', [AccountReportController::class, 'trailBalanceResult']);
        Route::get('/profit-loss', [AccountReportController::class, 'profitLoss'])->name('profit-loss');
        Route::post('/profit-loss', [AccountReportController::class, 'profitLossResult']);
        Route::get('/balance-sheet', [AccountReportController::class, 'balanceSheet'])->name('balance-sheet');
        Route::post('/balance-sheet', [AccountReportController::class, 'balanceSheetResult']);
    });
});
