<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Controller;
use App\Models\Logistic\FleetReqDetailResource;
use App\Models\Logistic\FleetTranOrderLocation;
use App\Models\Logistic\FleetTranOrderResource;
use App\Traits\SendNotification;
use DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransportationOrderResourceController extends Controller
{
    use SendNotification;

    public function removeSource(Request $request, $id, $resource_id)
    {
        $this->authorize('update-model-feature', FleetTranOrderResource::class);
        return DB::transaction(function () use ($resource_id) {
            $model = FleetTranOrderResource::findOrFail($resource_id);
            $model->delete();
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $model->req_detail_resource_id)->count();
            $detail_req = FleetReqDetailResource::findOrFail($model->req_detail_resource_id);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            $detail_req->save();
            return $this->responseSuccess(['resource' => $model, 'detail' => $detail_req]);
        });
    }
    public function removeSources(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetTranOrderResource::class);
        $resource_ids = $request->get('ids');
        return DB::transaction(function () use ($resource_ids) {
            $model = FleetTranOrderResource::with(['fleetTranOrderLocations'])->findOrFail($resource_ids[0]);
            // $resourceType = ResourceType::where('id', $model->resource_type_id)->first();
            foreach ($resource_ids as $resource_id) {
                $resource = FleetTranOrderResource::findOrFail($resource_id);
                // $info = [
                //     "tran_order_location_id" => $model->fleet_tran_order_location_id ?? null,
                //     "tran_order_id" => $model->fleetTranOrderLocations->delivery_order_id ?? null,
                //     "resource_id" => $resource->resource_id,
                // ];
                $resource->delete();
                // $this->sendNotificationToUser($resourceType, $info, MessageNotification::JOB_CANCELLED);
            }
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $model->req_detail_resource_id)->count();
            $detail_req = FleetReqDetailResource::findOrFail($model->req_detail_resource_id);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            if ($count_number_of_resource_assigned < $detail_req->number_of_resources_required) {
                $detail_req->state = '1-Chưa đáp ứng';
            }
            $detail_req->save();
            // FleetTranOrderResource::whereIn('id', $resource_ids)->delete();
            return $this->responseSuccess(['detail' => $detail_req]);
        });
    }
    public function addSources(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetTranOrderResource::class);
        $request->validate([
            'detail_id' => [
                'required',
                Rule::exists('fleet_tran_order_locations', 'id')->where(function (Builder $query) use ($id) {
                    return $query->where('delivery_order_id', $id);
                }),
            ],
            'resource_type_id' => ['required', 'exists:res_resource_types,id'],
            'req_detail_resource_id' => ['required', 'exists:fleet_req_detail_resources,id'],
            'resource_ids' => ['required', 'array'],
        ]);
        $info = $request->only(['detail_id', 'resource_type_id', 'resource_ids', 'req_detail_resource_id']);
        try {
            $model_resources = [];
            $resource_ids = $info['resource_ids'];
            DB::beginTransaction();
            foreach ($resource_ids as $resource_id) {
                $model_resource = FleetTranOrderResource::updateOrCreate([
                    'fleet_tran_order_location_id' => $info['detail_id'],
                    'resource_type_id' => $info['resource_type_id'],
                    'resource_id' => $resource_id,
                    'req_detail_resource_id' => $info['req_detail_resource_id'],
                ]);
                $model_resources[] = $model_resource;

            }
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $info['req_detail_resource_id'])->count();
            $detail_req = FleetReqDetailResource::findOrFail($info['req_detail_resource_id']);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            if ($count_number_of_resource_assigned >= $detail_req->number_of_resources_required) {
                $detail_req->state = '2-Đã đủ yêu cầu';
                $location = FleetTranOrderLocation::findOrFail($info['detail_id']);
                $location->status = '2-Cập nhật';
                $location->save();
            }

            $detail_req->save();
            $model_resource->refresh();
            DB::commit();
            return $this->responseSuccess(['resources' => $model_resources, 'detail' => $detail_req]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function addSource(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetTranOrderResource::class);
        $request->validate([
            'detail_id' => [
                'required',
                Rule::exists('fleet_tran_order_locations', 'id')->where(function (Builder $query) use ($id) {
                    return $query->where('delivery_order_id', $id);
                }),
            ],
            'resource_type_id' => ['required', 'exists:res_resource_types,id'],
            'req_detail_resource_id' => ['required', 'exists:fleet_req_detail_resources,id'],
            'resource_id' => ['required'],
        ]);
        $info = $request->only(['detail_id', 'resource_type_id', 'resource_id', 'req_detail_resource_id']);
        try {
            DB::beginTransaction();
            $model_resource = FleetTranOrderResource::updateOrCreate([
                'fleet_tran_order_location_id' => $info['detail_id'],
                'resource_type_id' => $info['resource_type_id'],
                'resource_id' => $info['resource_id'],
                'req_detail_resource_id' => $info['req_detail_resource_id'],
            ]);
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $info['req_detail_resource_id'])->count();
            $detail_req = FleetReqDetailResource::findOrFail($info['req_detail_resource_id']);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            $detail_req->save();
            $model_resource->refresh();

            // $model = ResourceType::where('id', $info['resource_type_id'])->first();
            // if ($model->group !== "VTCCDC") {
            //     $data = [
            //         "tran_order_location_id" => $info['detail_id'],
            //         "tran_order_id" => $id,
            //         "resource_id" => $info['resource_id'],
            //     ];
            //     $this->sendNotificationToUser($model, $data, MessageNotification::JOB_NEW);
            // }
            DB::commit();
            return $this->responseSuccess(['resource' => $model_resource, 'detail' => $detail_req]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
