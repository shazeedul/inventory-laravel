<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'chart_of_account_id',
        'financial_year_id',
        'account_sub_type_id',
        'account_sub_code_id',
        'account_voucher_type_id',
        'voucher_no',
        'voucher_date',
        'reference_type',
        'reference_id',
        'narration',
        'cheque_no',
        'cheque_date',
        'is_honour',
        'ledger_comment',
        'debit',
        'credit',
        'reverse_code',
        'reverse_sub_type_id',
        'reverse_sub_code_id',
        'is_closed_year',
    ];
}
