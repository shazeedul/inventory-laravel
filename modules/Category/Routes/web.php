<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

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

Route::prefix('admin/category')->as('admin.category.')->group(function () {
    Route::resource('/', CategoryController::class)->parameter('', 'category');
    Route::post('{category}/status-update', [CategoryController::class, 'statusUpdate'])->name('status-update');
});
