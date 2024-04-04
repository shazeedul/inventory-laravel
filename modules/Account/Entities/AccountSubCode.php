<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Account\Entities\AccountSubType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountSubCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_sub_type_id',
        'reference_id',
        'code',
        'name',
        'status',
    ];

    public function accountSubType()
    {
        return $this->belongsTo(AccountSubType::class, 'account_sub_type_id', 'id');
    }
}
