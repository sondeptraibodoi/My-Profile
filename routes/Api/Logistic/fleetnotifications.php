<?php
use App\Http\Controllers\Api\Logistic\FleetNotificationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('trans-order-notifications', [FleetNotificationController::class, 'listNotification']);
    Route::put('mark-read/{id}', [FleetNotificationController::class, 'markRead']);
    Route::put('mark-read-all', [FleetNotificationController::class, 'markReadAll']);
});
