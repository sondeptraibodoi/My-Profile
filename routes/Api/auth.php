<?php

use App\Http\Controllers\Api\Auth\AuthenticateController;
use App\Http\Controllers\Api\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Auth'], function () {
    Route::post('authenticate', [AuthenticateController::class, 'mobileAuthenticate']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', [AuthenticateController::class, 'logout']);
        Route::post('logout-device', [AuthenticateController::class, 'logoutDevice']);
        Route::put('editPasswordFirstLogin', [AuthenticateController::class, 'editPasswordFirstLogin']);
        Route::get('me', [ProfileController::class, 'me']);
        Route::post('editAvatar', [ProfileController::class, 'editAvatar']);
        Route::get('getAvatar', [ProfileController::class, 'getAvatar']);
        Route::get('get', [ProfileController::class, 'checkToken']);
        Route::put('editProfile', [ProfileController::class, 'editProfile']);
        Route::put('editPassword', [ProfileController::class, 'editPassword']);
    });
});
