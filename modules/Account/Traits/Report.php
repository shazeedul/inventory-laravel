<?php

namespace Modules\Account\Traits;

use Carbon\Carbon;
use Modules\Account\Entities\FinancialYear;
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
        $getYearDetail = FinancialYear::whereDate('start_date', '<=', $request->from_date)
            ->whereDate('end_date', '>=', $request->from_date)
            ->first();

        if ($getYearDetail == null) {
            return 0;
        }

        $previousFinanceYear = FinancialYear::whereDate('end_date', '<=', $getYearDetail->start_date)
            ->orderByDesc('end_date')
            ->first();

        if ($previousFinanceYear == null) {
            return 0;
        }

        $getOpeningBalance = AccountOpeningBalance::where('financial_year_id', $previousFinanceYear->id)
            ->where('chart_of_account_id',  $request->chart_of_account_id)
            ->when($accountSubCode, function ($q) use ($accountSubCode) {
                $q->where('account_sub_code_id', $accountSubCode);
            })
            ->get();

        $balanceResult = [];

        $coaDetail = ChartOfAccount::findOrFail($request->chart_of_account_id);

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

        $coaDetail = ChartOfAccount::findOrFail($request->chart_of_account_id);
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
        return AccountTransaction::where('chart_of_account_id', $request->chart_of_account_id)
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
        return AccountTransaction::where('chart_of_account_id', $request->chart_of_account_id)
            ->whereBetween('voucher_date', [$request->from_date, $request->to_date])
            ->when($accountSubCode, function ($q) use ($accountSubCode) {
                $q->where('account_sub_code_id', $accountSubCode);
            })
            ->sum('credit');
    }
}
