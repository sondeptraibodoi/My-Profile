<?php

use App\Http\Controllers\Api\IM\ProductVariantController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('im-product-variants', ProductVariantController::class);
});
