<?php

use App\Http\Controllers\ArtisanHttpController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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
Route::get('/', [DashboardController::class, 'redirectToDashboard'])->name('home');
Route::get('/home', [DashboardController::class, 'redirectToDashboard'])->name('home');
Route::get('/admin', [DashboardController::class, 'redirectToDashboard']);
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard/get-word-count', [DashboardController::class, 'getWordCount'])->name('admin.dashboard.get-word-count');
Route::get('/admin/dashboard/get-pie-chart-data', [DashboardController::class, 'getPieChartData'])->name('admin.dashboard.get-pie-chart-data');
Route::get('lang/{lang}', [LocalizationController::class, 'switchLang'])->name('lang.switch');
Route::get('dev/artisan-http/storage-link', [ArtisanHttpController::class, 'storageLink'])->name('artisan-http.storage-link');

Route::get('test', [TestController::class, 'index'])->name('test');
