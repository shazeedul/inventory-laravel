<?php

namespace Modules\Account\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Account\Entities\FinancialYear;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\AccountTransaction;
use Modules\Account\Entities\AccountOpeningBalance;

trait Report
{
    /**
     * Get Opening Balance
     * @param $request
     * @param $accountSubCode
     * @return float $openingBalance
     */
    public function getOpeningBalance($request, $accountSubCode = null)
    {
        // Retrieve cached data if it exists
        $cacheFinancialYear = Cache::get('financial_years');

        // Filter to get the current financial year
        if ($cacheFinancialYear) {
            $getYearDetail = $cacheFinancialYear->filter(function ($item) use ($request) {
                return $item->start_date <= $request->from_date && $item->end_date >= $request->from_date;
            })->first();
        } else {
            $getYearDetail = FinancialYear::whereDate('start_date', '<=', $request->from_date)
                ->whereDate('end_date', '>=', $request->from_date)
                ->first();
            Cache::put('financial_years', FinancialYear::all());
        }

        // If no financial year detail is found, return 0
        if ($getYearDetail === null) {
            return 0;
        }

        // Filter to get the previous financial year
        if ($cacheFinancialYear) {
            $previousFinanceYear = $cacheFinancialYear->filter(function ($item) use ($getYearDetail) {
                return $item->end_date <= $getYearDetail->start_date;
            })->sortByDesc('end_date')->first();
        } else {
            $previousFinanceYear = FinancialYear::whereDate('end_date', '<=', $getYearDetail->start_date)
                ->orderByDesc('end_date')
                ->first();
            Cache::put('financial_years', FinancialYear::all());
        }

        // If no previous financial year is found, return 0
        if ($previousFinanceYear === null) {
            return 0;
        }

        $getOpeningBalance = AccountOpeningBalance::where('financial_year_id', $previousFinanceYear->id)
            ->where('chart_of_account_id',  $request->chart_of_account_id)
            ->when($accountSubCode, function ($q) use ($accountSubCode) {
                $q->where('account_sub_code_id', $accountSubCode);
            })
            ->get();

        $balanceResult = [];

        $chartOfAccountCache = Cache::get('chart_of_accounts');
        if ($chartOfAccountCache) {
            $coaDetail = $chartOfAccountCache->firstWhere('id', $request->chart_of_account_id);
        } else {
            $coaDetail = ChartOfAccount::findOrFail($request->chart_of_account_id);
            Cache::put('chart_of_accounts', ChartOfAccount::all());
        }

        if ($coaDetail->account_type_id == 1 || $coaDetail->account_type_id == 4) {
            foreach ($getOpeningBalance as $value) {
                $balanceResult[] = number_format($value->debit,  2, '.',  '') - number_format($value->credit,  2, '.',  '');
            }
        }
        if ($coaDetail->account_type_id == 2 || $coaDetail->account_type_id == 3 || $coaDetail->account_type_id == 5) {
            foreach ($getOpeningBalance as $value) {
                $balanceResult[] = number_format($value->credit,  2, '.',  '') - number_format($value->debit,  2, '.',  '');
            }
        }

        $openingBalance = array_sum($balanceResult);
        $fromDate = Carbon::parse($request->from_date)->subDay(1)->format('Y-m-d') . '23:59:59';
        $transactions = AccountTransaction::with(['chartOfAccount', 'reversesCode'])
            ->where('chart_of_account_id',  $request->chart_of_account_id)
            ->whereBetween('voucher_date',  [$getYearDetail->start_date, $fromDate])
            ->get();

        foreach ($transactions as $transaction) {
            if ($transaction->chartOfAccount->account_type_id == 1 || $transaction->chartOfAccount->account_type_id == 4) {
                $openingBalance += number_format($transaction->debit,  2,  '.', '') - number_format($transaction->credit,  2,  '.', '');
            }

            if ($transaction->chartOfAccount->account_type_id == 2 || $transaction->chartOfAccount->account_type_id == 3 || $transaction->chartOfAccount->account_type_id == 5) {
                $openingBalance += number_format($transaction->credit,  2,  '.', '') - number_format($transaction->debit,  2,  '.', '');
            }
        }

        return $openingBalance;
    }

    /**
     * Get Transaction list
     * @param $request
     * @param float $getBalance
     * @return $transactionDetails
     */
    public function getTransactionDetail($request, $getBalance)
    {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();

        $transactionDetails = AccountTransaction::with(['chartOfAccount', 'reverseCode', 'voucherType'])
            ->where('chart_of_account_id', $request->chart_of_account_id)
            ->whereBetween('voucher_date', [$fromDate, $toDate])
            ->get();


        foreach ($transactionDetails as $key => $transactionData) {
            if ($transactionData->chartOfAccount->account_type_id == 1 || $transactionData->chartOfAccount->account_type_id == 4) {
                $getBalance += number_format($transactionData->debit,  2,  '.', '') - number_format($transactionData->credit,  2,  '.', '');
                $transactionDetails[$key]['balance'] = $getBalance;
            }

            if ($transactionData->chartOfAccount->account_type_id == 2 || $transactionData->chartOfAccount->account_type_id == 3 || $transactionData->chartOfAccount->account_type_id == 5) {
                $getBalance += number_format($transactionData->credit,  2,  '.', '') - number_format($transactionData->debit,  2,  '.', '');
                $transactionDetails[$key]['balance'] = $getBalance;
            }
        }

        return $transactionDetails;
    }

    /**
     * Get Closing Balance
     * @param $request
     * @param $accountSubCode
     * @return float $closingBalance
     */
    public function getClosingBalance($request, $accountSubCode = null)
    {
        $closingBalance = 0;
        $openingBalance = $this->getOpeningBalance($request, $accountSubCode);
        $debitBalance = $this->getDebitBalance($request, $accountSubCode);
        $creditBalance = $this->getCreditBalance($request, $accountSubCode);

        $coaDetail = Cache::has('chart_of_accounts') ? Cache::get('chart_of_accounts')->firstWhere('id', $request->chart_of_account_id)
            : ChartOfAccount::findOrFail($request->chart_of_account_id);
        if (in_array($coaDetail->account_type_id, [1, 4])) {
            $closingBalance = (float) $openingBalance + (float) $debitBalance - (float) $creditBalance;
        } else {
            $closingBalance = (float) $openingBalance + (float) $creditBalance - (float) $debitBalance;
        }

        return $closingBalance;
    }

    /**
     * Get Debit Balance
     * @param $request
     * @param $accountSubCode
     * @return float $debitBalance
     */
    public function getDebitBalance($request, $accountSubCode = null)
    {
        return (float) AccountTransaction::where('chart_of_account_id', $request->chart_of_account_id)
            ->whereBetween('voucher_date', [$request->from_date, $request->to_date])
            ->when($accountSubCode, function ($q) use ($accountSubCode) {
                $q->where('account_sub_code_id', $accountSubCode);
            })
            ->sum('debit');
    }

    /**
     * Get Credit Balance
     * @param $request
     * @param $accountSubCode
     * @return float $creditBalance
     */
    public function getCreditBalance($request, $accountSubCode = null)
    {
        return (float) AccountTransaction::where('chart_of_account_id', $request->chart_of_account_id)
            ->whereBetween('voucher_date', [$request->from_date, $request->to_date])
            ->when($accountSubCode, function ($q) use ($accountSubCode) {
                $q->where('account_sub_code_id', $accountSubCode);
            })
            ->sum('credit');
    }

    public function test($fromDate, $toDate, $voucherType)
    {
        $coaWithGroupBy = AccountVoucher::select('chart_of_account_id')
            ->where('account_voucher_type_id', $voucherType)
            ->where('is_approved', 1)
            ->whereBetween('voucher_date', [$fromDate, $toDate])
            ->selectRaw('chart_of_account_id, SUM(CASE WHEN account_voucher_type_id = 2 THEN credit ELSE debit END) as total_amount')
            ->groupBy('chart_of_account_id')
            ->get()
            ->keyBy('chart_of_account_id');

        $coaDetails = ChartOfAccount::where('is_active', 1)
            ->get()
            ->keyBy('id');

        // Get fourth-level COA IDs and their parent IDs
        $fourthLevelCoaIds = $coaWithGroupBy->keys();
        $fourthLevelCoaDetails = $coaDetails->filter(fn($value, $key) => $fourthLevelCoaIds->contains($key));
        $thirdLevelCoaIds = $fourthLevelCoaDetails->pluck('parent_id')->unique();

        // Retrieve third-level COA details and child COA details
        $thirdLevelCoaDetails = $coaDetails->filter(fn($value, $key) => $thirdLevelCoaIds->contains($key));
        $childOfThirdLevel = ChartOfAccount::whereIn('parent_id', $thirdLevelCoaIds)->get()->keyBy('id');

        // Prepare results
        $finalCollection = collect();

        foreach ($childOfThirdLevel as $child) {
            if ($coaWithGroupBy->has($child->id)) {
                // Fourth-level COA
                $finalCollection->push([
                    'id' => $child->id,
                    'name' => $child->name,
                    'parent_id' => $child->parent_id,
                    'total_amount' => $coaWithGroupBy[$child->id]->total_amount,
                ]);
            } elseif ($thirdLevelCoaDetails->has($child->parent_id)) {
                // Third-level COA: Aggregate child amounts
                $totalAmount = $childOfThirdLevel->where('parent_id', $child->id)
                    ->sum(fn($child) => $coaWithGroupBy->get($child->id, (object)['total_amount' => 0])->total_amount);

                $finalCollection->push([
                    'id' => $child->id,
                    'name' => $child->name,
                    'parent_id' => $child->parent_id,
                    'total_amount' => $totalAmount,
                ]);
            }
        }

        return [
            'thirdLabelFullCoaDetail' => $thirdLevelCoaDetails->values(),
            'childOfFourthLabelFinal' => $finalCollection,
        ];
    }

    /**
     * Get Periodic Closing Balance
     * @param $request
     * @param $accountSubCode
     * @return float $closingBalance
     */
    public function getPeriodicClosingBalance($request, $accountSubCode = null)
    {
        $closingBalance = 0;
        $debitBalance = $this->getDebitBalance($request, $accountSubCode);
        $creditBalance = $this->getCreditBalance($request, $accountSubCode);

        $coaDetail = Cache::has('chart_of_accounts') ? Cache::get('chart_of_accounts')->firstWhere('id', $request->chart_of_account_id)
            : ChartOfAccount::findOrFail($request->chart_of_account_id);
        if (in_array($coaDetail->account_type_id, [1, 4])) {
            $closingBalance = (float) $debitBalance - (float) $creditBalance;
        } else {
            $closingBalance = (float) $creditBalance - (float) $debitBalance;
        }

        return $closingBalance;
    }

    /**
     * Get Opening Balance By Year
     * @param $financial_year_id
     * @param $chart_of_account_id
     * @return float $openingBalance
     */
    public function getOpeningBalanceByYear($financial_year_id, $chart_of_account_id)
    {
        $getOpeningBalance = AccountOpeningBalance::where('financial_year_id', $financial_year_id)
            ->where('chart_of_account_id',  $chart_of_account_id)
            ->get();

        $balanceResult = [];

        $coaDetail = Cache::has('chart_of_accounts') ? Cache::get('chart_of_accounts')->firstWhere('id', $chart_of_account_id)
            : ChartOfAccount::findOrFail($chart_of_account_id);

        if ($coaDetail->account_type_id == 1 || $coaDetail->account_type_id == 4) {
            foreach ($getOpeningBalance as $value) {
                $balanceResult[] = number_format($value->debit,  2, '.',  '') - number_format($value->credit,  2, '.',  '');
            }
        }
        if ($coaDetail->account_type_id == 2 || $coaDetail->account_type_id == 3 || $coaDetail->account_type_id == 5) {
            foreach ($getOpeningBalance as $value) {
                $balanceResult[] = number_format($value->credit,  2, '.',  '') - number_format($value->debit,  2, '.',  '');
            }
        }

        $openingBalance = array_sum($balanceResult);

        return $openingBalance;
    }
}
