<?php

namespace Modules\Account\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountQuarter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'financial_year_id',
        'start_date',
        'end_date',
    ];
}
