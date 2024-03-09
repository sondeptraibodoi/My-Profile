<?php

use App\Http\Controllers\Api\HR\WorkerController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('workers-in-group', [WorkerController::class, 'listWorkerInGroup']);
});
