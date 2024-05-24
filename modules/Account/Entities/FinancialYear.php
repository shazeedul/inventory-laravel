<?php

namespace Modules\Account\Entities;

use Carbon\Carbon;
use App\Traits\DataTableActionBtn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialYear extends Model
{
    use HasFactory, DataTableActionBtn;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'is_closed',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => 'boolean',
        'is_closed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        static::updated(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
