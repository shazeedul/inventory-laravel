<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Account\Entities\FinancialYear;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function accountSubType()
    {
        return $this->belongsTo(AccountSubType::class, 'account_sub_type_id');
    }

    public function accountSubCode()
    {
        return $this->belongsTo(AccountSubCode::class, 'account_sub_code_id');
    }
}
