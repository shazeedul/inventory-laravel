<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\ChartOfAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountPredefine extends Model
{
    use HasFactory;

    protected $table = 'account_predefine';

    protected $fillable = [
        'key',
        'chart_of_account_id',
        'created_by',
        'updated_by',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
