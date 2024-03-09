<?php

use App\Http\Controllers\Api\YeuCau\FleetReqStatusController;
use App\Http\Controllers\Api\YeuCau\FleetReqTypeController;
use App\Http\Controllers\Api\YeuCau\FleetTranOrderStatusController;
use App\Http\Controllers\Api\YeuCau\ResourceTypeController;
use App\Http\Controllers\Api\YeuCau\ResourceTypeFeatureController;
use App\Http\Controllers\Api\YeuCau\ResourceTypeStatisticalController;
use App\Http\Controllers\Api\YeuCau\TransportationOrderController;
use App\Http\Controllers\Api\YeuCau\TransportationOrderDetailController;
use App\Http\Controllers\Api\YeuCau\TransportationOrderResourceController;
use App\Http\Controllers\Api\YeuCau\TransportationRequestController;
use App\Http\Controllers\Api\YeuCau\TransportationRequestDetailController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'yeu-cau'], function () {
    Route::get('tai-nguyens/{id}/features-group', [ResourceTypeFeatureController::class, 'indexGroup']);
    Route::get('tai-nguyens/{id}/features', [ResourceTypeFeatureController::class, 'index']);
    Route::get('tai-nguyens/thong-ke', [ResourceTypeStatisticalController::class, 'index']);

    Route::apiResource('don-van-chuyens', TransportationOrderController::class);
    Route::apiResource('don-van-chuyen-dia-chis', TransportationOrderDetailController::class)->only(['index', 'show']);
    Route::get('dia-chi-tra-hang-list', [TransportationRequestController::class, 'getLocationWarehouse']);
    Route::get('yeu-cau-van-chuyens/nguoi-tao', [TransportationRequestController::class, 'userCreateReq']);
    Route::apiResource('yeu-cau-van-chuyens', TransportationRequestController::class);
    Route::patch('yeu-cau-van-chuyens/{id}/trang-thai', [TransportationRequestController::class, 'updateStatus']);
    Route::patch('yeu-cau-van-chuyens/{id}/ca-lam-viec', [TransportationRequestController::class, 'updateShift']);
    Route::delete('yeu-cau-van-chuyens/{id}/trang-thai-da-xoa', [TransportationRequestController::class, 'softDelete']);
    Route::put('yeu-cau-van-chuyens/{id}/update-time', [TransportationRequestController::class, 'updateTime']);
    Route::patch('ca-lam-viec/{id}/yeu-cau-van-chuyens', [TransportationRequestController::class, 'updateShifts']);
    Route::put('don-van-chuyen-dia-chi-tai-nguyens/{id}', [TransportationRequestDetailController::class, 'updateDetailDieuKien']);

    Route::delete('don-van-chuyen-dia-chi-tai-nguyens/{id}/all', [TransportationRequestDetailController::class, 'removeDetailDieuKienAllResource']);

    Route::get('yeu-cau-van-chuyens/{id}/khoi-phuc-da-xoa', [TransportationRequestController::class, 'restoreSoftDelete']);

    Route::post('don-van-chuyens/{id}/tai-nguyen', [TransportationOrderResourceController::class, 'addSource']);
    Route::post('don-van-chuyens/{id}/tai-nguyens', [TransportationOrderResourceController::class, 'addSources']);
    Route::delete('don-van-chuyens/{id}/tai-nguyen/{resource_od}', [TransportationOrderResourceController::class, 'removeSource']);
    Route::delete('don-van-chuyens/{id}/tai-nguyens', [TransportationOrderResourceController::class, 'removeSources']);

    Route::patch('don-van-chuyens/{id}/trang-thai', [TransportationOrderController::class, 'updateStatus']);
    Route::patch('don-van-chuyens/{id}/ca-lam-viec', [TransportationOrderController::class, 'updateShift']);
    Route::group(['middleware' => ['cacheResponse:600']], function () {
        Route::apiResource('dieu-kien-yeu-cau-van-chuyens', ResourceTypeController::class)->only(['index']);
        Route::apiResource('trang-thai-yeu-cau-van-chuyens', FleetReqStatusController::class)->only(['index']);
        Route::apiResource('trang-thai-don-van-chuyens', FleetTranOrderStatusController::class)->only(['index']);
        Route::apiResource('loai-yeu-cau-van-chuyens', FleetReqTypeController::class)->only(['index']);
    });
});
