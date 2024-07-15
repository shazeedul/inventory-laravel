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
use Modules\Account\Entities\AccountOpeningBalance;

class AccountReportController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('permission:read_account_report');

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
}
