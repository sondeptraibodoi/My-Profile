<?php

namespace App\Http\Controllers\Api\Logistic;

use App\Http\Controllers\Api\BaseController;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\HR\EmployeeView;
use App\Models\Logistic\FleetNotification;
use App\Models\Logistic\FleetVehicle;
use App\Models\System\ResourceType;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use Illuminate\Http\Request;

class FleetNotificationController extends BaseController
{
    use ResponseType;
    protected $repository;
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetNotification::class);
        $this->extra_list = function ($query, $request) {
            $user = $request->user();
            $employee_id = EmployeeView::where('partner_id', $user->partner_id)->first()->id;
            $vehicle_id = FleetVehicle::where('driver_id', $employee_id)->get()->pluck('id')->toArray();
            $resource_type_id = ResourceType::where('group', 'Phương tiện')->get()->pluck('id')->toArray();
            $query->allowedFilters(
                'tran_order_id',
                'tran_order_location_id',
                'tran_order_resource_id',
                'resource_id',
                'resource_type_id',
                'message',
                'status',
            )
                ->orderBy('time_notification', 'desc')
                ->whereIn('resource_id', $vehicle_id ?? [])
                ->whereIn('resource_type_id', $resource_type_id);
        };
    }
    public function listNotification(Request $request)
    {
        $user = $request->user();
        $query = $this->repository->getModel()::query();
        $query = QueryBuilder::for($query, $request)
            ->allowedFilters(
                'tran_order_id',
                'tran_order_location_id',
                'tran_order_resource_id',
                'resource_id',
                'resource_type_id',
                'message',
                'status',
                "time_notification"
            )
            ->orderBy('time_notification', 'desc')
            ->where('user_id', $user->getKey());
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function markRead(Request $request, $id)
    {
        $model = $this->repository->getModel()::query();
        $notification = $model->where('id', $id)->first();
        $notification->update(['status' => '2-Đã đọc']);
        return $this->responseSuccess();
    }
    public function markReadAll(Request $request)
    {
        $user = $request->user();
        $model = $this->repository->getModel()::query();
        $notifications = $model->where('user_id', $user->getKey())->get();
        foreach ($notifications as $notification) {
            $notification->update(['status' => '2-Đã đọc']);
        }
        return $this->responseSuccess();
    }
}
