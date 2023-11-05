<?php

namespace Modules\Product\Entities;

use App\Models\User;
use Modules\Unit\Entities\Unit;
use App\Traits\DataTableActionBtn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\Category\Entities\Category;
use Modules\Supplier\Entities\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, DataTableActionBtn;

    protected $fillable = [
        'unit_id',
        'category_id',
        'name',
        'quantity',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        if (Auth::check()) {
            static::creating(function ($model) {
                $model->created_by = Auth::id();
            });
            static::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Status list.
     */
    public static function statusList(): array
    {
        return [
            '0' => 'Inactive',
            '1' => 'Active',
        ];
    }
}
