<?php

namespace Modules\Purchase\Entities;

use App\Models\User;
use App\Traits\DataTableActionBtn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Modules\Purchase\Entities\PurchaseDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Supplier\Entities\Supplier;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_no',
        'date',
        'supplier_id',
        'total_price',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        if (Auth::check()) {
            static::creating(function ($model) {
                $model->created_by = Auth::id();
                $model->purchase_no = rand(100000, 999999);
                $model->date = now();
            });
            static::updating(function ($model) {
                $model->updated_by = Auth::id();
            });
        }
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 0);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 1);
    }

    public function scopeTotalPrice($query)
    {
        return $query->sum('total_price');
    }

    public function scopeTotalPurchase($query)
    {
        return $query->count();
    }

    public function scopeTotalPending($query)
    {
        return $query->where('status', 0)->count();
    }

    public function scopeTotalApproved($query)
    {
        return $query->where('status', 1)->count();
    }

    public function getPurchaseDateAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // public function scopeTotalPurchaseByMonth($query)
    // {
    //     return $query->selectRaw('MONTH(date) as month, SUM(total_price) as total_price')
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();
    // }

    // public function scopeTotalPurchaseByYear($query)
    // {
    //     return $query->selectRaw('YEAR(date) as year, SUM(total_price) as total_price')
    //         ->groupBy('year')
    //         ->orderBy('year')
    //         ->get();
    // }

    // public function scopeTotalPurchaseByDate($query, $date)
    // {
    //     return $query->whereDate('date', $date)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByMonthYear($query, $month, $year)
    // {
    //     return $query->whereMonth('date', $month)->whereYear('date', $year)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonth($query, $year, $month)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDate($query, $year, $month, $date)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateSupplier($query, $year, $month, $date, $supplier)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('supplier_id', $supplier)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateSupplierCategory($query, $year, $month, $date, $supplier, $category)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('supplier_id', $supplier)->where('category_id', $category)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateSupplierCategoryProduct($query, $year, $month, $date, $supplier, $category, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('supplier_id', $supplier)->where('category_id', $category)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateCategory($query, $year, $month, $date, $category)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('category_id', $category)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateCategoryProduct($query, $year, $month, $date, $category, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('category_id', $category)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthDateProduct($query, $year, $month, $date, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->whereDate('date', $date)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthSupplier($query, $year, $month, $supplier)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('supplier_id', $supplier)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthSupplierCategory($query, $year, $month, $supplier, $category)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('supplier_id', $supplier)->where('category_id', $category)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthSupplierCategoryProduct($query, $year, $month, $supplier, $category, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('supplier_id', $supplier)->where('category_id', $category)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthCategory($query, $year, $month, $category)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('category_id', $category)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthCategoryProduct($query, $year, $month, $category, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('category_id', $category)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearMonthProduct($query, $year, $month, $product)
    // {
    //     return $query->whereYear('date', $year)->whereMonth('date', $month)->where('product_id', $product)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearSupplier($query, $year, $supplier)
    // {
    //     return $query->whereYear('date', $year)->where('supplier_id', $supplier)->sum('total_price');
    // }

    // public function scopeTotalPurchaseByYearSupplierCategory($query, $year, $supplier, $category)
    // {
    //     return $query->whereYear('date', $year)->where('supplier_id', $supplier)->where('category_id', $category)->sum('total_price');
    // }
}
