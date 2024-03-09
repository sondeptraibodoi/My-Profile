<?php
use App\Http\Controllers\Api\Logistic\TransportOrderController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/transport-orders', [TransportOrderController::class, 'index']);
    Route::get('/transport-orders-leader', [TransportOrderController::class, 'listTransOrderWorker']);
    Route::get('/transport-orders/{id}', [TransportOrderController::class, 'showOrder']);
    Route::get('/transport-orders-leader/{id}', [TransportOrderController::class, 'showOrderLeader']);
    Route::post('/add-worker/{id}', [TransportOrderController::class, 'addWorkerToTransOrder']);
    Route::delete('/remove-worker', [TransportOrderController::class, 'removeWorkers']);
});
