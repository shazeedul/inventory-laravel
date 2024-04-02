<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountSubType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'status',
    ];
}
