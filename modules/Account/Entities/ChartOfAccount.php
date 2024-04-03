<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'head_level',
        'account_type_id',
        'is_cash_nature',
        'is_bank_nature',
        'is_budget',
        'is_depreciation',
        'is_subtype',
        'account_sub_type_id',
        'is_stock',
        'is_fixed_asset_schedule',
        'depreciation_rate',
        'note_no',
        'asset_code',
        'depreciation_code',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
