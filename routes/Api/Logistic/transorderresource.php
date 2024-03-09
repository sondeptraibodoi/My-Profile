<?php
use App\Http\Controllers\Api\Logistic\TranOrderResourceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/transport-order-resource/{id}', [TranOrderResourceController::class, 'show']);
    Route::post('/transport-order-resource/reject/{id}', [TranOrderResourceController::class, 'reject']);
    Route::post('/transport-order-resource/accept/{id}', [TranOrderResourceController::class, 'accept']);
    Route::get('/refusal-reason', [TranOrderResourceController::class, 'getRefusalReason']);
});
