<?php

namespace App\Http\Controllers\Api\Logistic;

use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\Logistic\FleetDeliveryOrder;
use App\Models\Logistic\FleetDoNotification;
use App\Models\Logistic\FleetRefusalReason;
use App\Models\Logistic\FleetTranOrderResource;
use App\Models\Logistic\FleetTransportationRequest;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use Illuminate\Http\Request;

class TranOrderResourceController extends Controller
{
    use ResponseType;

    protected $repository;
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTranOrderResource::class, [FleetTranOrderResource::LOG_NAME]);
    }
    public function getRefusalReason(Request $request)
    {
        $query = FleetRefusalReason::query()->where('active', true);
        $query = QueryBuilder::for($query, $request)
            ->initFromModelForList()
            ->allowedPagination();
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason_for_refusal_id' => 'required',
        ], [
            'reason_for_refusal_id.required' => __('Vui lòng chọn lý do từ chối'),
        ]);
        $info = $request->all();
        $resource = FleetTranOrderResource::where('id', $id)->first();
        $resource->update([
            'reason_for_refusal_id' => $info['reason_for_refusal_id'],
            'detail_of_reason' => $info['detail_of_reason'],
            'acceptance' => '2-Từ chối',
        ]);
        return $this->responseSuccess();
    }
    public function accept(Request $request, $id)
    {
        $request->validate([
            'tran_order_location_id' => 'required', // from trans_order_location
            'destination_id' => 'required', // from trans_order_location
            'tr_req_id' => 'required', // from trans_orders
            'tran_order_date' => 'required', // from trans_orders
        ], [
            'tran_order_location_id.required' => __('Cần phải có thông tin địa điểm'),
            'destination_id.required' => __('Cần phải có thông tin địa điểm'),
            'tr_req_id.required' => __('Cần phải có thông tin yêu cầu vận chuyển'),
            'tran_order_date.required' => __('Cần phải có thông tin lệnh điều xe'),
        ], [
            'tran_order_location_id' => __('file-attachment.field.tran_order_location_id'),
            'destination_id' => __('file-attachment.field.destination_id'),
            'tr_req_id' => __('file-attachment.field.tr_req_id'),
            'tran_order_date' => __('file-attachment.field.tran_order_date'),
        ]);
        $info = $request->all();
        $transOrderReq = FleetTransportationRequest::where('id', $info['tr_req_id'])->with(['locations'])->first()->toArray();
        $transOrderReqLocations = $transOrderReq['locations'];
        $req_location = null;
        foreach ($transOrderReqLocations as $location) {
            if ($location['destination_id'] = $info['destination_id']) {
                $req_location = $location;
            }
        }
        if ($req_location === null) {
            abort(404, 'Không thể chấp nhận yêu cầu này');
        }
        $resource = FleetTranOrderResource::where('id', $id)->first();
        $resource->update([
            'acceptance' => '1-Đồng ý',
        ]);
        $deliveryOrder = FleetDeliveryOrder::create([
            'fleet_to_resource_id' => $id,
            'from_id' => $transOrderReq['from_id'] ?? null,
            'destination_id' => $info['destination_id'] ?? null,
        ]);
        FleetDoNotification::create([
            "fleet_delivery_order_location_id" => $deliveryOrder->getKey(),
            "state" => '1-Mới tạo yêu cầu giao hàng',
            "req_location_id" => $req_location['id'],
            "req_date" => $transOrderReq['start_date'],
            "tran_order_location_id" => $info['tran_order_location_id'],
            "tran_order_date" => $info['tran_order_date'],
            "shift_id" => $info['shift_id'],
            "destination_id" => $info['destination_id'] ?? null,
        ]);
        return $this->responseSuccess();
    }
    public function show(Request $request, $id)
    {
        $resource = FleetTranOrderResource::where('id', $id)->with(['refusalReason', 'transOrderLocation.transportOrder.shift', 'transOrderLocation.pickupAddress'])->get();
        return $this->responseSuccess($resource);
    }
}
