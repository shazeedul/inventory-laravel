<?php

namespace Modules\Account\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Account\Entities\FinancialYear;
use Illuminate\Contracts\Support\Renderable;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\DataTables\DayBookDataTable;
use Modules\Account\Entities\AccountTransaction;
use Modules\Account\Entities\AccountVoucherType;
use Modules\Account\DataTables\BankBookDataTable;
use Modules\Account\DataTables\CashBookDataTable;
use Modules\Account\DataTables\SubLedgerDataTable;
use Modules\Account\DataTables\NoteLedgerDataTable;
use Modules\Account\Entities\AccountOpeningBalance;
use Modules\Account\DataTables\ControlLedgerDataTable;
use Modules\Account\DataTables\GeneralLedgerDataTable;
use Modules\Account\Entities\AccountPredefine;
use Modules\Account\Traits\Report;

class AccountReportController extends Controller
{
    use Report;

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

    /**
     * Sub Ledger
     */
    public function subLedger(SubLedgerDataTable $dataTable)
    {
        $subTypes = AccountSubType::where('status', 1)->get();
        $accounts = ChartOfAccount::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 0)->where('is_bank_nature', 0)->get();
        return $dataTable->render('account::reports.sub_ledger', compact('accounts'));
    }

    /**
     * Control Ledger
     */
    public function controlLedger(ControlLedgerDataTable $dataTable)
    {
        $accounts = ChartOfAccount::where('is_active', 1)->where('head_level', 3)->get();
        return $dataTable->render('account::reports.control_ledger', compact('accounts'));
    }

    /**
     * Note Ledger
     */
    public function noteLedger(NoteLedgerDataTable $dataTable)
    {
        $accounts = ChartOfAccount::where('is_active', 1)->where('head_level', 3)->whereNotNull('note_no')->get();
        return $dataTable->render('account::reports.note_ledger', compact('accounts'));
    }

    /**
     * Receive And Payment
     */
    public function receivePayment()
    {
        return view('account::reports.receive_payment');
    }

    /**
     * Receive And Payment Result
     */
    public function receivePaymentResult(Request $request)
    {
        if ($request->voucher_date != null) {
            $dateRange = explode(" to ", request()->input('voucher_date'));
            $fromDate = Carbon::createFromFormat('Y-m-d', $dateRange[0])->format('Y-m-d');
            $toDate = Carbon::createFromFormat('Y-m-d', $dateRange[1])->format('Y-m-d');
        } else {
            $fromDate = Carbon::now()->subDay(30)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        }

        $type = $request->type;

        if ($request->voucher_date == null || $type == null) {
            return abort(500);
        }

        // Query for cash nature accounts
        $cashChartAccounts = ChartOfAccount::where('head_level', 4)
            ->where('account_type_id', 1)
            ->where('is_cash_nature', 1)
            ->get();

        // Query for bank nature accounts
        $bankChartAccounts = ChartOfAccount::where('head_level', 4)
            ->where('account_type_id', 1)
            ->where('is_bank_nature', 1)
            ->get();

        $sumCashNature = 0;
        $sumClosingCashNature = 0;

        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;
        foreach ($cashChartAccounts as $key => $value) {
            $request['chart_of_account_id'] = $value->id;
            $openingBalance = $this->getOpeningBalance($request);
            $sumCashNature += number_format($openingBalance, 2, '.', '');
            $closingBalance = $this->getClosingBalance($request);
            $sumClosingCashNature += number_format($closingBalance, 2, '.', '');
        }
        $cashNatureParentId = $cashChartAccounts->first()->parent_id ?? null;

        $sumBankNature = 0;
        $sumClosingBankNature = 0;
        foreach ($bankChartAccounts as $key => $value) {
            $request['chart_of_account_id'] = $value->id;
            $openingBalance = $this->getOpeningBalance($request);
            $sumBankNature += number_format($openingBalance, 2, '.', '');
            $closingBalance = $this->getClosingBalance($request);
            $sumClosingBankNature += number_format($closingBalance, 2, '.', '');
        }
        $bankNatureParentId = $bankChartAccounts->first()->parent_id ?? null;

        $cashNatureParent = ChartOfAccount::findOrFail($cashNatureParentId);
        $cashNatureParent->totalOpening = $sumCashNature;
        $cashNatureParent->totalClosing = $sumClosingCashNature;
        $bankNatureParent = ChartOfAccount::findOrFail($bankNatureParentId);
        $bankNatureParent->totalOpening = $sumBankNature;
        $bankNatureParent->totalClosing = $sumClosingBankNature;

        $advancePredefine = AccountPredefine::where('key', 'advance')->first();

        $advanceOpeningBalance = 0;
        $advanceClosingBalance = 0;

        if ($advancePredefine) {
            $advanceHead = ChartOfAccount::where('is_active', 1)
                ->where('head_level', 4)
                ->where('parent_id', $advancePredefine->chart_of_account_id)
                ->get();

            $advanceLedger = ChartOfAccount::findOrFail($advancePredefine->chart_of_account_id);

            foreach ($advanceHead as $key => $value) {
                $request['cash_chart_accounts'] = $value->id;
                $openingBalance = $this->getOpeningBalance($request);
                $advanceOpeningBalance += number_format($openingBalance, 2, '.', '');
                $closingBalance = $this->getClosingBalance($request);
                $advanceClosingBalance += number_format($closingBalance, 2, '.', '');
            }

            $advanceLedger->totalOpening = $advanceOpeningBalance;
            $advanceLedger->totalClosing = $advanceClosingBalance;
        }

        $receiptThirdLabelCoaFullDetail = $this->test($fromDate, $toDate, 2);

        $receiptThirdLevelDetail = $receiptThirdLabelCoaFullDetail['thirdLabelFullCoaDetail'];
        $receiptFourthLevelFinal = $receiptThirdLabelCoaFullDetail['childOfFourthLabelFinal'];

        $paymentThirdLabelCoaFullDetail = $this->test($fromDate, $toDate, 1);

        $paymentThirdLevelDetail = $paymentThirdLabelCoaFullDetail['thirdLabelFullCoaDetail'];
        $paymentFourthLevelFinal = $paymentThirdLabelCoaFullDetail['childOfFourthLabelFinal'];

        return view('account::reports.result.receive_payment', compact(
            'cashNatureParent',
            'bankNatureParent',
            'advanceLedger',
            'receiptThirdLevelDetail',
            'receiptFourthLevelFinal',
            'paymentThirdLevelDetail',
            'paymentFourthLevelFinal',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Trail Balance
     */
    public function trailBalance()
    {
        return view('account::reports.trail_balance');
    }

    /**
     * Profit Loss
     */
    public function profitLoss()
    {
        return view('account::reports.profit_loss');
    }

    /**
     * Balance Sheet
     */
    public function balanceSheet()
    {
        return view('account::reports.balance_sheet');
    }
}
