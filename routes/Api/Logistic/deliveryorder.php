<?php
use App\Http\Controllers\Api\Logistic\DeliveryOrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::put('/delivery-orders/{id}', [DeliveryOrderController::class, 'updateDeliveryOrder']);
    Route::get('/delivery-orders/{fleet_to_resource_id}', [DeliveryOrderController::class, 'showDeliveryOrder']);
    Route::get('/delivery-orders', [DeliveryOrderController::class, 'getDeliveryOrder']);
    Route::put('/delivery-orders/{id}/next-status', [DeliveryOrderController::class, 'updateNextStatus']);
    Route::put('/delivery-orders/{id}/cancel', [DeliveryOrderController::class, 'cancelDeliveryOrder']);
});
