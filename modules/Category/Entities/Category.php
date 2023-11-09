<?php

namespace Modules\Category\Entities;

use App\Traits\DataTableActionBtn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Category\Database\factories\CategoryFactory;

class Category extends Model
{
    use HasFactory, DataTableActionBtn;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
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
