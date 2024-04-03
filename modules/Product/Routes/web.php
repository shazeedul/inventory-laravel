<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;
use Modules\Product\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin/product')->as('admin.product.')->group(function () {
    Route::resource('/', ProductController::class)->parameter('', 'product');
    Route::post('{product}/status-update', [ProductController::class, 'statusUpdate'])->name('status-update');
});

Route::prefix('admin/category')->as('admin.category.')->group(function () {
    Route::resource('/', CategoryController::class)->parameter('', 'category');
    Route::post('{category}/status-update', [CategoryController::class, 'statusUpdate'])->name('status-update');
});
