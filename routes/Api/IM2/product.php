<?php

use App\Http\Controllers\Api\IM\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('im-products', ProductController::class);
    // Route::post('list-product', [ProductController::class, 'index']);
    Route::get('list-units', [ProductController::class, 'units']);
});
