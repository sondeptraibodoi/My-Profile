<?php

namespace App\Http\Controllers\Api\Logistic;

use App\Http\Controllers\Api\BaseController;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\Logistic\FleetDeliveryOrder;
use App\Models\System\FileAttachment;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use Exception;
use Illuminate\Http\Request;

class DeliveryOrderController extends BaseController
{
    use ResponseType;

    protected $repository;
    protected $statuses = ['1-Kế hoạch', '2-Đang thực hiện', '3-Hoàn thành'];
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetDeliveryOrder::class, [FleetDeliveryOrder::LOG_NAME]);
    }
    public function getDeliveryOrder(Request $request, $id)
    {
        $query = $this->repository->getModel()::query();
        $query = QueryBuilder::for($query, $request)
            ->allowedSearch([
                'id',
                'fleet_to_resource_id',
                'from_id',
                'start_date',
                'start_time',
                'pickup_date',
                'pickup_time',
                'break_time',
                'end_of_delivery_date',
                'end_of_delivery_time',
                'destination_id',
                'end_date',
                'end_time',
                'notes',
                'state'])
            ->allowedSorts([
                'id',
                'fleet_to_resource_id',
                'from_id',
                'start_date',
                'start_time',
                'pickup_date',
                'pickup_time',
                'break_time',
                'end_of_delivery_date',
                'end_of_delivery_time',
                'destination_id',
                'end_date',
                'end_time',
                'notes',
                'state'])
            ->allowedPagination();
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function updateDeliveryOrder(Request $request, $id)
    {
        $info = $request->all();
        $this->repository->update($id, $info);
        return $this->responseSuccess();
    }
    public function showDeliveryOrder(Request $request, $fleet_to_resource_id)
    {
        try {
            $deliveryOrder = $this->repository->getModel()::query()->where('fleet_to_resource_id', $fleet_to_resource_id)->with(['transOrderResource'])->first();
            $statuses = collect($this->statuses);
            $statusKey = $statuses->search($deliveryOrder->state);

            $res_model = $request->get('res_model', 'fleet_delivery_orders');
            $image = FileAttachment::query();
            $image->where('res_model', $res_model);
            $image->where('res_id', $deliveryOrder->id);

            return $this->responseSuccess([
                'delivery_order' => $deliveryOrder,
                'next_status' => $statuses->count() - 1 === $statusKey ? $this->statuses[$statusKey] : $this->statuses[$statusKey + 1],
                'image' => $image->get(),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function updateNextStatus(Request $request, $id)
    {
        try {
            $currentState = $request->get('state');
            if ($currentState != '3-Hoàn thành') {
                $statuses = collect($this->statuses);
                $statusKey = $statuses->search($currentState);
                $deliveryOrder = $this->repository->getModel()::query()->where('id', $id)->first();
                $deliveryOrder->update([
                    'state' => $this->statuses[$statusKey + 1],
                ]);
                return $this->responseSuccess();
            } else {
                abort(400, 'Không thể xác định trạng thái tiếp theo');
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function cancelDeliveryOrder($id)
    {
        $deliveryOrder = $this->repository->getModel()::query()->where('id', $id)->first();
        $deliveryOrder->update([
            'state' => '4-Hủy do sự cố',
        ]);
        return $this->responseSuccess();
    }
}
