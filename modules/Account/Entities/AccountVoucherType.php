<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountVoucherType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'is_active',
    ];
}
