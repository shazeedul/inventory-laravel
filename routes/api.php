<?php

use App\Http\Controllers\Api\VisitorCounterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::domain(config('domain.api'))->group(function () {
    Route::prefix('visitor')->as('api.visitor.')->group(function () {
        Route::get('/counter', [VisitorCounterController::class, 'index'])->name('counter');
        Route::get('/counter.svg', [VisitorCounterController::class, 'svg'])->name('counter.svg');
    });
});
