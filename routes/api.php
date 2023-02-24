<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('stores', [StoreController::class, 'index']);
Route::post('stores', [StoreController::class, 'create']);

Route::middleware(['ensure_store_is_valid'])->group(function () {
    Route::get('stores/{store_hash}', [StoreController::class, 'get']);
    Route::put('stores/{store_hash}', [StoreController::class, 'update']);
    Route::delete('stores/{store_hash}', [StoreController::class, 'delete']);
});

Route::middleware(['ensure_store_and_product_is_valid'])->group(function () {
    Route::post('stores/{store_hash}/{product_hash}', [ProductController::class, 'sell']);
});
