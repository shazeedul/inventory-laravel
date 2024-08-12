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
     * Trail Balance Result
     */
    public function trailBalanceResult(Request $request)
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

        $chartOfAccounts = ChartOfAccount::with([
            'secondChild' => function ($secondQ) {
                $secondQ->with([
                    'thirdChild' => function ($thirdQ) {
                        $thirdQ->with(['fourthChild']);
                    }
                ]);
            }
        ])->where('is_active', 1)->parentHead()->get();

        // Initialize variables for the trail balance
        $tableFooter = [
            'totalOpeningDebitBalance' => 0,
            'totalOpeningCreditBalance' => 0,
            'totalClosingDebitBalance' => 0,
            'totalClosingCreditBalance' => 0,
            'totalTransactionDebitBalance' => 0,
            'totalTransactionCreditBalance' => 0,
        ];
        $trailBalance = [];
        $newKey = 0;

        $getOpeningBalance = function ($request) {
            return $this->getOpeningBalance($request);
        };
        $getClosingBalance = function ($request) {
            return $this->getClosingBalance($request);
        };
        $getDebitBalance = function ($request) {
            return $this->getDebitBalance($request);
        };
        $getCreditBalance = function ($request) {
            return $this->getCreditBalance($request);
        };

        // Function to process each account and its children
        function processAccount($account, &$trailBalance, $fromDate, $toDate, &$newKey, $getOpeningBalance, $getClosingBalance, $getDebitBalance, $getCreditBalance)
        {
            $newKey++;
            $balance = [
                'id' => $account->id,
                'code' => $account->code,
                'name' => $account->name,
                'account_type_id' => $account->account_type_id,
                'parent_id' => $account->parent_id,
                'head_level' => $account->head_level,
            ];
            // Convert array to new request object
            $param = new \Illuminate\Http\Request([
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'chart_of_account_id' => $account->id,
            ]);

            if (in_array($account->account_type_id, [1, 4])) {
                $balance['opening_balance_debit'] = $getOpeningBalance($param);
                $balance['opening_balance_credit'] = 0;
                $balance['closing_balance_debit'] = $getClosingBalance($param);
                $balance['closing_balance_credit'] = 0;
            } elseif (in_array($account->account_type_id, [2, 3, 5])) {
                $balance['opening_balance_debit'] = 0;
                $balance['opening_balance_credit'] = $getOpeningBalance($param);
                $balance['closing_balance_debit'] = 0;
                $balance['closing_balance_credit'] = $getClosingBalance($param);
            }

            $balance['tran_balance_debit'] = $getDebitBalance($param);
            $balance['tran_balance_credit'] = $getCreditBalance($param);

            $trailBalance[$newKey] = $balance;

            // Process secondChild
            if ($account->secondChild->count() > 0) {
                foreach ($account->secondChild as $secondChild) {
                    processAccount($secondChild, $trailBalance, $fromDate, $toDate, $newKey, $getOpeningBalance, $getClosingBalance, $getDebitBalance, $getCreditBalance);

                    // Process thirdChild
                    if ($secondChild->thirdChild->count() > 0) {
                        foreach ($secondChild->thirdChild as $thirdChild) {
                            processAccount($thirdChild, $trailBalance, $fromDate, $toDate, $newKey, $getOpeningBalance, $getClosingBalance, $getDebitBalance, $getCreditBalance);

                            // Process fourthChild
                            if ($thirdChild->fourthChild->count() > 0) {
                                foreach ($thirdChild->fourthChild as $fourthChild) {
                                    processAccount($fourthChild, $trailBalance, $fromDate, $toDate, $newKey, $getOpeningBalance, $getClosingBalance, $getDebitBalance, $getCreditBalance);
                                }
                            }
                        }
                    }
                }
            }
        }

        // Iterate through chart of accounts and process each
        foreach ($chartOfAccounts as $account) {
            processAccount($account, $trailBalance, $fromDate, $toDate, $newKey, $getOpeningBalance, $getClosingBalance, $getDebitBalance, $getCreditBalance);
        }

        // Sum up values for each category
        $tableFooter['totalOpeningDebitBalance'] = array_sum(array_column($trailBalance, 'opening_balance_debit'));
        $tableFooter['totalOpeningCreditBalance'] = array_sum(array_column($trailBalance, 'opening_balance_credit'));
        $tableFooter['totalClosingDebitBalance'] = array_sum(array_column($trailBalance, 'closing_balance_debit'));
        $tableFooter['totalClosingCreditBalance'] = array_sum(array_column($trailBalance, 'closing_balance_credit'));
        $tableFooter['totalTransactionDebitBalance'] = array_sum(array_column($trailBalance, 'tran_balance_debit'));
        $tableFooter['totalTransactionCreditBalance'] = array_sum(array_column($trailBalance, 'tran_balance_credit'));

        return view('account::reports.result.trail_balance', compact([
            'trailBalance',
            'tableFooter',
            'type',
        ]));
    }

    /**
     * Profit Loss
     */
    public function profitLoss()
    {
        return view('account::reports.profit_loss');
    }

    /**
     * Profit Loss Result
     */
    public function profitLossResult(Request $request)
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

        // Convert array to new request object
        $param = new \Illuminate\Http\Request([
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);


        // Incomes
        $incomes = ChartOfAccount::with(['thirdChild' => function ($q) {
            $q->with(['fourthChild' => function ($q) {
                $q->where('account_type_id', 3);
            }])->where('account_type_id', 3);
        }])
            ->where('is_active', 1)
            ->where('parent_id', 3)
            ->get();

        $incomeBalance = 0;

        foreach ($incomes as $income) {
            $level2IncomeBalance = 0;
            foreach ($income->thirdChild as $income3) {
                $levelThreeBalance = 0;
                foreach ($income3->fourthChild as $income4) {
                    $param['chart_of_account_id'] = $income4->id;
                    if ($type == 'as_on') {
                        $balance = $this->getClosingBalance($param);
                    } else {
                        $balance = $this->getPeriodicClosingBalance($param);
                    }
                    $income4->setAttribute('balance', $balance);
                    $levelThreeBalance += $balance;
                }
                $income3->setAttribute('balance', $levelThreeBalance);
                $level2IncomeBalance += $levelThreeBalance;
            }
            $income->setAttribute('balance', $level2IncomeBalance);
            $incomeBalance += $level2IncomeBalance;
        }

        // Expenses
        $expenses = ChartOfAccount::with(['thirdChild' => function ($q) {
            $q->with(['fourthChild' => function ($q) {
                $q->where('account_type_id', 4);
            }])->where('account_type_id', 4);
        }])
            ->where('is_active', 1)
            ->where('parent_id', 4)
            ->get();

        $expenseBalance = 0;

        foreach ($expenses as $expense) {
            $level2ExpenseBalance = 0;
            foreach ($expense->thirdChild as $expense3) {
                $levelThreeBalance = 0;
                foreach ($expense3->fourthChild as $expense4) {
                    $param['chart_of_account_id'] = $expense4->id;
                    if ($type == 'as_on') {
                        $balance = $this->getClosingBalance($param);
                    } else {
                        $balance = $this->getPeriodicClosingBalance($param);
                    }
                    $expense4->setAttribute('balance', $balance);
                    $levelThreeBalance += $balance;
                }
                $expense3->setAttribute('balance', $levelThreeBalance);
                $level2ExpenseBalance += $levelThreeBalance;
            }
            $expense->setAttribute('balance', $level2ExpenseBalance);
            $expenseBalance += $level2ExpenseBalance;
        }

        $netProfit = $incomeBalance - $expenseBalance;
        $netLoss = $expenseBalance - $incomeBalance;

        return view('account::reports.result.profit_loss', compact([
            'incomes',
            'incomeBalance',
            'expenses',
            'expenseBalance',
            'fromDate',
            'toDate',
            'type',
            'netProfit',
            'netLoss',
        ]));
    }

    /**
     * Balance Sheet
     */
    public function balanceSheet()
    {
        return view('account::reports.balance_sheet');
    }

    /**
     * Balance Sheet Result
     */
    public function balanceSheetResult(Request $request)
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

        // Convert array to new request object
        $param = new \Illuminate\Http\Request([
            'from_date' => $fromDate,
            'to_date' => $toDate,
        ]);

        // Current Year
        $currentYear = FinancialYear::where('status', 1)->where('is_closed', 0)->first();
        // Last Three Years
        $lastThreeYears = FinancialYear::where('status', 0)->where('is_closed', 1)->orderBy('id', 'desc')->limit(3)->get();

        // Assets
        $assets = ChartOfAccount::with(['thirdChild' => function ($q) {
            $q->with(['fourthChild' => function ($q) {
                $q->where('account_type_id', 1);
            }])->where('account_type_id', 1);
        }])
            ->where('is_active', 1)
            ->where('parent_id', 1)
            ->get();

        $assetBalance = 0;

        foreach ($assets as $asset) {
            $level2AssetBalance = 0;
            $level2AssetYearBalances = [];

            foreach ($asset->thirdChild as $asset3) {
                $level3AssetBalance = 0;
                $level3AssetYearBalances = [];

                foreach ($asset3->fourthChild as $asset4) {
                    $param['chart_of_account_id'] = $asset4->id;
                    $balance = $this->getClosingBalance($param);
                    $asset4->setAttribute('balance', $balance);
                    $level3AssetBalance += $balance;

                    // Last Three Years
                    foreach ($lastThreeYears as $year) {
                        $yearKey = $year->name;
                        $yearBalance = $this->getOpeningBalanceByYear($year->id, $asset4->id);
                        $asset4->setAttribute("year_balance_{$yearKey}", $yearBalance);

                        // Accumulate the year balance for fourth level
                        if (!isset($level3AssetYearBalances[$yearKey])) {
                            $level3AssetYearBalances[$yearKey] = 0;
                        }
                        $level3AssetYearBalances[$yearKey] += $yearBalance;
                    }
                }
                $asset3->setAttribute('balance', $level3AssetBalance);
                $level2AssetBalance += $level3AssetBalance;
                // Set the accumulated year balances for third level
                foreach ($lastThreeYears as $year) {
                    $yearKey = $year->name;
                    $asset3->setAttribute("year_balance_{$yearKey}", $level3AssetYearBalances[$yearKey]);

                    // Accumulate the year balance for second level
                    if (!isset($level2AssetYearBalances[$yearKey])) {
                        $level2AssetYearBalances[$yearKey] = 0;
                    }
                    $level2AssetYearBalances[$yearKey] += $level3AssetYearBalances[$yearKey];
                }
            }
            $asset->setAttribute('balance', $level2AssetBalance);
            $assetBalance += $level2AssetBalance;

            // Set the accumulated year balances for second level
            foreach ($lastThreeYears as $year) {
                $yearKey = $year->name;
                $asset->setAttribute("year_balance_{$yearKey}", $level2AssetYearBalances[$yearKey]);
            }
        }

        // Liabilities
        $liabilities = ChartOfAccount::with(['thirdChild' => function ($q) {
            $q->with(['fourthChild' => function ($q) {
                $q->where('account_type_id', 2);
            }])->where('account_type_id', 2);
        }])
            ->where('is_active', 1)
            ->where('parent_id', 2)
            ->get();

        $liabilityBalance = 0;

        foreach ($liabilities as $liability) {
            $level2LiabilityBalance = 0;
            $level2LiabilityYearBalances = [];

            foreach ($liability->thirdChild as $liability3) {
                $level3LiabilityBalance = 0;
                $level3LiabilityYearBalances = [];

                foreach ($liability3->fourthChild as $liability4) {
                    $param['chart_of_account_id'] = $liability4->id;
                    $balance = $this->getClosingBalance($param);
                    $liability4->setAttribute('balance', $balance);
                    $level3LiabilityBalance += $balance;

                    // Last Three Years
                    foreach ($lastThreeYears as $year) {
                        $yearKey = $year->name;
                        $yearBalance = $this->getOpeningBalanceByYear($year->id, $liability4->id);
                        $liability4->setAttribute("year_balance_{$yearKey}", $yearBalance);

                        // Accumulate the year balance for third level
                        if (!isset($level3LiabilityYearBalances[$yearKey])) {
                            $level3LiabilityYearBalances[$yearKey] = 0;
                        }
                        $level3LiabilityYearBalances[$yearKey] += $yearBalance;
                    }
                }

                $liability3->setAttribute('balance', $level3LiabilityBalance);
                $level2LiabilityBalance += $level3LiabilityBalance;

                // Set the accumulated year balances for third level
                foreach ($lastThreeYears as $year) {
                    $yearKey = $year->name;
                    $liability3->setAttribute("year_balance_{$yearKey}", $level3LiabilityYearBalances[$yearKey] ?? 0);

                    // Accumulate the year balance for second level
                    if (!isset($level2LiabilityYearBalances[$yearKey])) {
                        $level2LiabilityYearBalances[$yearKey] = 0;
                    }
                    $level2LiabilityYearBalances[$yearKey] += $level3LiabilityYearBalances[$yearKey] ?? 0;
                }
            }

            $liability->setAttribute('balance', $level2LiabilityBalance);
            $liabilityBalance += $level2LiabilityBalance;

            // Set the accumulated year balances for second level
            foreach ($lastThreeYears as $year) {
                $yearKey = $year->name;
                $liability->setAttribute("year_balance_{$yearKey}", $level2LiabilityYearBalances[$yearKey] ?? 0);
            }
        }

        // Share Equity
        $shareEquities = ChartOfAccount::with(['thirdChild' => function ($q) {
            $q->with(['fourthChild' => function ($q) {
                $q->where('account_type_id', 5);
            }])->where('account_type_id', 5);
        }])
            ->where('is_active', 1)
            ->where('parent_id', 5)
            ->get();

        $shareEquityBalance = 0;

        foreach ($shareEquities as $shareEquity) {
            $level2ShareEquityBalance = 0;
            $level2ShareEquityYearBalances = [];
            foreach ($shareEquity->thirdChild as $shareEquity3) {
                $level3ShareEquityBalance = 0;
                $level3ShareEquityYearBalances = [];
                foreach ($shareEquity3->fourthChild as $shareEquity4) {
                    $param['chart_of_account_id'] = $shareEquity4->id;
                    $balance = $this->getClosingBalance($param);
                    $shareEquity4->setAttribute('balance', $balance);
                    $level3ShareEquityBalance += $balance;

                    // Last Three Years
                    foreach ($lastThreeYears as $year) {
                        $yearKey     = $year->name;
                        $yearBalance = $this->getOpeningBalanceByYear($year->id, $shareEquity4->id);
                        $shareEquity4->setAttribute("year_balance_{$yearKey}", $yearBalance);
                        $level3ShareEquityYearBalances[$yearKey] = $yearBalance;
                    }
                }
                $shareEquity3->setAttribute('balance', $level3ShareEquityBalance);
                $level2ShareEquityBalance += $level3ShareEquityBalance;
                $$shareEquity3->setAttribute("year_balance{$yearKey}", $level3ShareEquityBalance);
            }
            $shareEquity->setAttribute('balance', $level2ShareEquityBalance);
            $shareEquityBalance += $level2ShareEquityBalance;
            foreach ($lastThreeYears as $year) {
                $shareEquity->setAttribute("year_balance{$yearKey}", $level2ShareEquityBalance);
            }
        }

        return view('account::reports.result.t_balance_sheet', compact(
            'assets',
            'assetBalance',
            'liabilities',
            'liabilityBalance',
            'shareEquities',
            'shareEquityBalance',
            'currentYear',
            'lastThreeYears',
            'fromDate',
            'toDate',
            'type'
        ));
    }
}
