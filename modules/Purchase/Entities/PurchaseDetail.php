<?php

namespace Modules\Purchase\Entities;

use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Purchase\Entities\Purchase;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePending($query)
    {
        return $query->whereHas('purchase', function ($query) {
            $query->where('status', 0);
        });
    }
}
