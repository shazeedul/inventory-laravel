<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Account\Entities\AccountSubType;
use Modules\Account\Entities\AccountTransaction;
use Modules\Account\Entities\AccountType;
use Modules\Account\Entities\AccountVoucher;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'head_level',
        'parent_id',
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
            static::created(function ($model) {
                $model->created_by = Auth::id();
                $model->code = self::generateAccCode($model->head_level, $model->parent_id);
            });
            static::updated(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    protected static function generateAccCode(int $head_level, int $parent_id)
    {
        $key = ChartOfAccount::where('parent_id', $parent_id)->count();
        $p_acc_code = ChartOfAccount::find($parent_id);
        $code = null;

        $p_acc_code = $p_acc_code->code;

        switch ($head_level) {
            case 2:
                $code = $p_acc_code . sprintf('%01d', ($key ?? 0 + 1));
                break;

            case 3:
                $code = $p_acc_code . sprintf('%02d', ($key ?? 0 + 1));
                break;

            case 4:
                $code = $p_acc_code . sprintf('%03d', ($key ?? 0 + 1));
                break;

            case 5:
                $code = $p_acc_code . sprintf('%03d', ($key ?? 0 + 1));
                break;

            default:
                $code = null;
                break;
        }

        return $code;
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
    protected static function parentAccounts(int $head_level, int $type_id)
    {
        $head_levels = [];
        for ($i = 1; $i < (int) $head_level; $i++) {
            array_push($head_levels, $i);
        }

        return self::whereIn('head_level', $head_levels)->where('account_type_id', $type_id)->where('is_active', 1)->get([
            "id",
            "code",
            "name",
            "head_level",
            "parent_id",
            "account_type_id",
            "is_cash_nature",
            "is_bank_nature",
            "is_budget",
            "is_depreciation",
            "is_subtype",
            "account_sub_type_id",
            "is_stock",
            "is_fixed_asset_schedule",
            "depreciation_rate",
            "note_no",
            "asset_code",
            "depreciation_code",
        ]);
    }
}
