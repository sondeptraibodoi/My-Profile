<?php

use App\Http\Controllers\Api\FileAttachmentController;
use Illuminate\Support\Facades\Route;

Route::get('file-attachments/{id}', [FileAttachmentController::class, 'show']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('file-attachments', [FileAttachmentController::class, 'index']);
    Route::delete('file-attachments/{id}', [FileAttachmentController::class, 'destroy']);
    Route::post('upload-file', [FileAttachmentController::class, 'uploadFile']);
    Route::post('upload-image', [FileAttachmentController::class, 'uploadImage']);
    Route::get('download-file/{id}', [FileAttachmentController::class, 'downloadFile']);
    Route::get('download-all', [FileAttachmentController::class, 'downloadAll']);
});
