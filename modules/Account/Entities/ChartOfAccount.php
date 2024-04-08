<?php

namespace Modules\Account\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountType;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\AccountVoucher;
use Modules\Account\Entities\AccountTransaction;
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

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            static::creating(function ($model) {
                $model->created_by = Auth::id();
                $model->code = self::generateAccCode($model->head_level, $model->parent_id);
            });
            static::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    private function generateAccCode(int $head_level, int $parent_id)
    {
        $key = ChartOfAccount::where('parent_id', $parent_id)->count();
        $p_acc_code = ChartOfAccount::find($parent_id);
        $account_code = null;

        $p_acc_code = $p_acc_code->account_code;

        switch ($head_level) {
            case 2:
                $account_code = $p_acc_code . sprintf('%01d', ($key ?? 0 + 1));
                break;

            case 3:
                $account_code = $p_acc_code . sprintf('%02d', ($key ?? 0 + 1));
                break;

            case 4:
                $account_code = $p_acc_code . sprintf('%03d', ($key ?? 0 + 1));
                break;

            case 5:
                $account_code = $p_acc_code . sprintf('%03d', ($key ?? 0 + 1));
                break;

            default:
                $account_code = null;
                break;
        }

        return $account_code;
    }

    public function secondChild()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id', 'id')->where('head_level', 2);
    }

    public function thirdChild()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id', 'id')->where('head_level', 3);
    }

    public function fourthChild()
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id', 'id')->where('head_level', 4);
    }

    public function accountVoucher()
    {
        return $this->hasMany(AccountVoucher::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function accountSubType()
    {
        return $this->belongsTo(AccountSubType::class);
    }

    public function accountTransaction()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    //get all parent accounts of a given chart of account.
    static function getParentAccounts($id)
    {
        $head_levels = [];
    }
}
