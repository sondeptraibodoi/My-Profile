<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Models\Logistic\FleetReqDetailResource;
use App\Models\Logistic\FleetTranOrderLocation;
use App\Models\Logistic\FleetTranOrderResource;
use App\Repositories\BaseRepository;
use App\Traits\SendNotification;
use Illuminate\Http\Request;

class TransportationRequestDetailController extends BaseController
{
    use SendNotification;

    public function __construct()
    {
        $this->repository = new BaseRepository(FleetReqDetailResource::class);
    }
    public function updateDetailDieuKien(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetReqDetailResource::class);
        $data = $request->all();
        $detail = FleetReqDetailResource::where('id', $id);
        $model_detail = $detail->first();
        $model_detail->fill($data);
        $model_detail->save();
        $detail_location = $detail->with(['detailLocation.orderLocation'])->first()->toArray();
        $location = FleetTranOrderLocation::findOrFail($detail_location['detail_location']['order_location']['id']);
        $location->status = '2-Cập nhật';
        $location->save();
        return $this->responseSuccess($model_detail);
    }
    public function removeDetailDieuKienAllResource(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetReqDetailResource::class);
        FleetTranOrderResource::where('req_detail_resource_id', $id)->delete();
        $count_number_of_resource_assigned = 0;
        $model = FleetReqDetailResource::findOrFail($id);
        $model->number_of_resource_assigned = $count_number_of_resource_assigned;
        $model->save();
        return $this->responseSuccess($model);
    }
}
