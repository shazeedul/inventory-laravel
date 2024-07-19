<?php

namespace Modules\Account\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\AccountTransaction;
use Modules\Account\DataTables\BankBookDataTable;
use Modules\Account\DataTables\CashBookDataTable;
use Modules\Account\DataTables\DayBookDataTable;
use Modules\Account\DataTables\GeneralLedgerDataTable;
use Modules\Account\Entities\AccountOpeningBalance;
use Modules\Account\Entities\AccountVoucherType;

class AccountReportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('permission:read_account_report');
        $this->middleware('auth');
        \cs_set('theme', [
            'title' => 'Account Reports',
            'back' => \back_url(),
            'breadcrumb' => [
                [
                    'name' => 'Dashboard',
                    'link' => route('admin.dashboard'),
                ],
                [
                    'name' => 'Account Reports',
                    'link' => false,
                ],
            ],
            'rprefix' => 'admin.account.report',
        ]);
    }

    /**
     * Cash Book
     */
    public function cashBook(CashBookDataTable $dataTable)
    {
        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->get();
        return $dataTable->render('account::reports.cash_book', compact('accounts'));
    }

    /**
     * Bank Book
     */
    public function bankBook(BankBookDataTable $dataTable)
    {
        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_bank_nature', 1)->get();
        return $dataTable->render('account::reports.bank_book', compact('accounts'));
    }

    /**
     * Day Book
     */
    public function dayBook(DayBookDataTable $dataTable)
    {
        $voucherTypes = AccountVoucherType::where('is_active', 1)->get();
        return $dataTable->render('account::reports.day_book', compact('voucherTypes'));
    }

    /**
     * General Ledger
     */
    public function generalLedger(GeneralLedgerDataTable $dataTable)
    {
        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 0)->where('is_bank_nature', 0)->get();
        return $dataTable->render('account::reports.general_ledger', compact('accounts'));
    }
}
