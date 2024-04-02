<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
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
}
