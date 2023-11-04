<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'category_id',
        'product_id',
        'quantity',
        'unit_price',
        'price',
        'description',
    ];

    protected $casts = [
        'quantity' => 'double',
        'unit_price' => 'double',
        'price' => 'double',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function scopePending($query)
    {
        return $query->whereHas('purchase', function ($query) {
            $query->where('status', 0);
        });
    }
}
