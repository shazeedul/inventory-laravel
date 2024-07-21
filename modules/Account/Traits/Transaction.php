<?php

namespace Modules\Account\Traits;

use Carbon\Carbon;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountTransaction;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\FinancialYear;

trait Transaction
{
    /**
     * $debit = Debit Account
     * $credit = Credit Account
     * $amount = Total Amount
     * $type = Voucher Type, 1 for Debit, 2 for Credit, 3 for Contra, 4 for journal, 5 for Cash Payment, 6 for Cash Receive, 7 for Bank Payment, 8 for Bank Receive, 9 for Note
     * $isDebit = Voucher Amount is Debit: 1 or Credit: 0
     * $reference = Reference form voucher create (e.g. invoice no)
     * $customerId = Customer Id
     * $remarks = Reference form voucher create
     * $date = Voucher Date
     * $comment = Ledger Comment
     * $isAuto = Is Auto Voucher
     */
    public function voucherWithApprove(int $debit, int $credit, float $amount, int $type, bool $isDebit, string $reference, int $customerId = null, string $remarks = null, $date, string $comment = null, bool $isAuto = false): void
    {
        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        $lastVoucherId = AccountVoucher::orderBy('id', 'DESC')->value('id') ?? 0;
        $lastVoucherId = $lastVoucherId + 1;

        $voucher = new AccountVoucher();
        $voucher->financial_year_id = $financial_year->id;
        $voucher->voucher_date = $date ?? Carbon::today()->toDateString();
        $voucher->account_voucher_type_id = $type;
        $voucher->chart_of_account_id = $debit;
        $voucher->reverse_code = $credit;
        $voucher->narration = $remarks;
        $voucher->reference_type = $reference;

        $debit_account = ChartOfAccount::whereNotNull('account_sub_type_id')->find($debit);
        $credit_account = ChartOfAccount::whereNotNull('account_sub_type_id')->find($credit);
        // Sub Code for Debit Account
        if ($debit_account && $customerId) {
            $debitSubCode = AccountSubCode::where('account_sub_type_id', $debit_account->account_sub_type_id)->where('reference_id', $customerId)->first();
        } else {
            $debitSubCode = null;
        }
        // Sub Code for Credit Account
        if ($credit_account && $customerId) {
            $creditSubCode = AccountSubCode::where('account_sub_type_id', $credit_account->account_sub_type_id)->where('reference_id', $customerId)->first();
        } else {
            $creditSubCode = null;
        }

        $voucher->account_sub_type_id = $debit_account->account_sub_type_id ?? null;
        $voucher->account_sub_code_id = $debitSubCode->id ?? null;
        $voucher->reverse_sub_type_id = $credit_account->account_sub_type_id ?? null;
        $voucher->reverse_sub_code_id = $creditSubCode->id ?? null;
        $voucher->ledger_comment = $comment;
        $voucher->is_auto = $isAuto;
        if ($isDebit) {
            $voucher->debit = $amount;
        } else {
            $voucher->credit = $amount;
        }
        $voucher->created_at = Carbon::now();
        $voucher->voucher_no = $this->getVoucherType($type) . '-' . str_pad($lastVoucherId, 6, '0', STR_PAD_LEFT);
        $voucher->save();

        // Approve Voucher
        $transaction = new AccountTransaction();
        $transaction->voucher_no = $voucher->voucher_no;
        $transaction->voucher_date = $voucher->voucher_date;
        $transaction->chart_of_account_id = $voucher->chart_of_account_id;
        $transaction->financial_year_id = $voucher->financial_year_id;
        $transaction->account_sub_type_id = $voucher->account_sub_type_id;
        $transaction->account_sub_code_id = $voucher->account_sub_code_id;
        $transaction->account_voucher_type_id = $voucher->account_voucher_type_id;
        $transaction->reference_type = $voucher->reference_type;
        $transaction->reference_id = $voucher->reference_id;
        $transaction->narration = $voucher->narration;
        $transaction->ledger_comment = $voucher->ledger_comment;
        if ($isDebit) {
            $transaction->debit = $voucher->debit;
        } else {
            $transaction->credit = $voucher->credit;
        }
        $transaction->reverse_code = $voucher->reverse_code;
        $transaction->reverse_sub_type_id = $voucher->reverse_sub_type_id;
        $transaction->reverse_sub_code_id = $voucher->reverse_sub_code_id;
        $transaction->is_auto = $voucher->is_auto;
        $transaction->save();
        // Reverse Entry
        $reverse = new AccountTransaction();
        $reverse->voucher_no = $voucher->voucher_no;
        $reverse->voucher_date = $voucher->voucher_date;
        $reverse->chart_of_account_id = $voucher->reverse_code;
        $reverse->financial_year_id = $voucher->financial_year_id;
        $reverse->account_sub_type_id = $voucher->reverse_sub_type_id;
        $reverse->account_sub_code_id = $voucher->reverse_sub_code_id;
        $reverse->account_voucher_type_id = $voucher->account_voucher_type_id;
        $reverse->reference_type = $voucher->reference_type;
        $reverse->reference_id = $voucher->reference_id;
        $reverse->narration = $voucher->narration;
        $reverse->ledger_comment = $voucher->ledger_comment;
        if ($isDebit) {
            $reverse->credit = $voucher->debit;
        } else {
            $reverse->debit = $voucher->credit;
        }
        $reverse->reverse_code = $voucher->chart_of_account_id;
        $reverse->reverse_sub_type_id = $voucher->account_sub_type_id;
        $reverse->reverse_sub_code_id = $voucher->account_sub_code_id;
        $reverse->is_auto = $voucher->is_auto;
        $reverse->save();

        $voucher->is_approved = true;
        $voucher->approved_by = auth()->id();
        $voucher->approved_at = Carbon::now();
        $voucher->save();
    }

    /**
     * $debit = Debit Account
     * $credit = Credit Account
     * $amount = Total Amount
     * $type = Voucher Type, 1 for Debit, 2 for Credit, 3 for Contra, 4 for journal, 5 for Cash Payment, 6 for Cash Receive, 7 for Bank Payment, 8 for Bank Receive, 9 for Note
     * $isDebit = Voucher Amount is Debit: 1 or Credit: 0
     * $reference = Reference form voucher create (e.g. invoice no)
     * $customerId = Customer Id
     * $remarks = Reference form voucher create
     * $date = Voucher Date
     * $comment = Ledger Comment
     * $isAuto = Is Auto Voucher
     */
    public function voucherWithoutApprove(int $debit, int $credit, float $amount, int $type, bool $isDebit, string $reference, int $customerId = null, string $remarks = null, $date, string $comment = null, bool $isAuto = false): void
    {
        $financial_year = FinancialYear::where('status', true)->where('is_closed', false)->first();
        $lastVoucherId = AccountVoucher::orderBy('id', 'DESC')->value('id') ?? 0;
        $lastVoucherId = $lastVoucherId + 1;

        $voucher = new AccountVoucher();
        $voucher->financial_year_id = $financial_year->id;
        $voucher->voucher_date = $date ?? Carbon::today()->toDateString();
        $voucher->account_voucher_type_id = $type;
        $voucher->chart_of_account_id = $debit;
        $voucher->narration = $remarks;
        $voucher->reference_type = $reference;

        $debit_account = ChartOfAccount::whereNotNull('account_sub_type_id')->find($debit);
        $credit_account = ChartOfAccount::whereNotNull('account_sub_type_id')->find($credit);
        // Sub Code for Debit Account
        if ($debit_account && $customerId) {
            $debitSubCode = AccountSubCode::where('account_sub_type_id', $debit_account->account_sub_type_id)->where('reference_no', $customerId)->first();
        } else {
            $debitSubCode = null;
        }
        // Sub Code for Credit Account
        if ($credit_account && $customerId) {
            $creditSubCode = AccountSubCode::where('account_sub_type_id', $credit_account->account_sub_type_id)->where('reference_no', $customerId)->first();
        } else {
            $creditSubCode = null;
        }

        $voucher->account_sub_type_id = $debit_account->account_sub_type_id ?? null;
        $voucher->account_sub_code_id = $debitSubCode->id ?? null;
        $voucher->reverse_sub_type_id = $credit_account->account_sub_type_id ?? null;
        $voucher->reverse_sub_code_id = $creditSubCode->id ?? null;
        $voucher->ledger_comment = $comment;
        $voucher->is_auto = $isAuto;
        if ($isDebit) {
            $voucher->debit = $amount;
        } else {
            $voucher->credit = $amount;
        }
        $voucher->created_at = Carbon::now();
        $voucher->voucher_no = $this->getVoucherType($type) . '-' . str_pad($lastVoucherId, 6, '0', STR_PAD_LEFT);
        $voucher->save();
    }

    public function getVoucherType(int $type): string
    {
        switch ($type) {
            case 1:
                return 'DV';
            case 2:
                return 'CV';
            case 3:
                return 'TV';
            case 4:
                return 'JV';
            case 5:
                return 'CP';
            case 6:
                return 'CR';
            case 7:
                return 'BP';
            case 8:
                return 'BR';
            case 9:
                return 'NV';
            default:
                return 'JV';
        }
    }
}
