<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\FinancialYear;
use Modules\Account\Entities\AccountSubCode;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\ChartOfAccount;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Account\Entities\AccountVoucherType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTransaction extends Model
{
    use HasFactory, SoftDeletes;

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
        'is_auto'
    ];

    protected $dates = ['voucher_date', 'cheque_date'];

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class);
    }

    public function accountSubType()
    {
        return $this->belongsTo(AccountSubType::class, 'account_sub_type_id', 'id');
    }

    public function accountSubCode()
    {
        return $this->belongsTo(AccountSubCode::class, 'account_sub_code_id', 'id');
    }

    public function voucherType()
    {
        return $this->belongsTo(AccountVoucherType::class, 'account_voucher_type_id', 'id');
    }

    public function reverseCode()
    {
        return $this->belongsTo(ChartOfAccount::class, 'reverse_code', 'id');
    }

    public function reverseSubType()
    {
        return $this->belongsTo(AccountSubType::class, 'reverse_sub_type_id');
    }

    public function reverseSubCode()
    {
        return $this->belongsTo(AccountSubCode::class, 'reverse_sub_code_id');
    }

    public function getVoucherDateAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function scopeVoucherNo($query, $voucher_no)
    {
        return $query->with(['accountSubCode', 'accountSubType', 'reverseSubCode', 'reverseSubType'])->where('voucher_no', $voucher_no);
    }

    public function getAllVouchersByNoAttribute()
    {
        return $this->voucherNo($this->voucher_no)->get();
    }
}
