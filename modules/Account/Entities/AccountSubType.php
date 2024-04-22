<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSubType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    /**
     * Relation with AccountSubCode model.
     *
     */
    public function accountSubCodes()
    {
        return $this->hasMany(AccountSubCode::class, 'account_sub_type_id', 'id');
    }
}
