<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountOpeningBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'chart_of_account_id',
        'financial_year_id',
        'account_sub_type_id',
        'account_sub_code_id',
        'debit',
        'credit',
        'opening_date',
        'created_by',
        'updated_by',
    ];
}
