<?php

namespace App\Http\Controllers\Api\Logistic;

use App\Http\Controllers\Api\BaseController;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\HR\EmployeeView;
use App\Models\Logistic\FleetReqDetailResource;
use App\Models\Logistic\FleetTranOrderLocation;
use App\Models\Logistic\FleetTranOrderResource;
use App\Models\Logistic\FleetTransportOrder;
use App\Models\Logistic\FleetVehicle;
use App\Models\System\ResourceType;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use App\Traits\SendNotification;
use DB;
use Illuminate\Http\Request;

class TransportOrderController extends BaseController
{
    use ResponseType;
    use SendNotification;
    protected $repository;
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTransportOrder::class, [FleetTransportOrder::LOG_NAME]);
        $this->extra_list = function ($query, $request) {
            $user = $request->user();
            $employee_id = EmployeeView::where('partner_id', $user->partner_id)->first()->id;
            $vehicle_id = FleetVehicle::where('driver_id', $employee_id)->get()->pluck('id')->toArray();
            $resource_type_ids = ResourceType::where('group', 'Phương tiện')->get()->pluck('id')->toArray();
            $query->allowedFilters(
                'code',
                'tr_req_id',
                'type_id',
                'from_id',
                'destination_id',
                'start_date',
                'start_time',
                'end_date',
                'end_time',
                'shift_id',
                'created_by_id',
                'status_id',
                'updated_by_id',
                'created_at'
            )
                ->orderBy('created_at', 'desc')
                ->with(['transOrderLocation.transOrderResource' => function ($q) use ($vehicle_id) {
                    $q->whereIn('resource_id', $vehicle_id ?? []);
                },
                    'transOrderLocation' => function ($q) use ($vehicle_id) {
                        $q->whereHas('transOrderResource', function ($q2) use ($vehicle_id) {
                            $q2->whereIn('resource_id', $vehicle_id ?? []);
                        });
                    },
                    'transOrderLocation.transOrderResource.refusalReason',
                    'transOrderLocation.pickupAddress',
                    'transOrderLocation.pickupAddress.parent',
                    'shift'])
                ->whereHas('transOrderLocation.transOrderResource', function ($q) use ($vehicle_id, $resource_type_ids) {
                    $q->whereIn('resource_type_id', $resource_type_ids);
                    $q->whereIn('resource_id', $vehicle_id ?? []);
                    $q->where('notification_sent', true);
                });
        };
    }

    public function showOrder(Request $request, $id)
    {
        $user = $request->user();
        $employee_id = EmployeeView::where('partner_id', $user->partner_id)->first()->id;
        $vehicle_id = FleetVehicle::where('driver_id', $employee_id)->get()->pluck('id')->toArray();
        $resource_type_ids = ResourceType::where('group', 'Phương tiện')->get()->pluck('id')->toArray();
        $transOrder = FleetTransportOrder::where('id', $id)
            ->with(['transOrderLocation.transOrderResource' => function ($q) use ($vehicle_id) {
                $q->whereIn('resource_id', $vehicle_id ?? []);
            },
                'transOrderLocation' => function ($q) use ($vehicle_id) {
                    $q->whereHas('transOrderResource', function ($q2) use ($vehicle_id) {
                        $q2->whereIn('resource_id', $vehicle_id ?? []);
                    });
                },
                'transOrderLocation.transOrderResource.refusalReason',
                'transOrderLocation.pickupAddress',
                'transOrderLocation.pickupAddress.parent',
                'shift'])
            ->whereHas('transOrderLocation.transOrderResource', function ($q) use ($vehicle_id, $resource_type_ids) {
                $q->whereIn('resource_type_id', $resource_type_ids);
                $q->whereIn('resource_id', $vehicle_id ?? []);
            })->first();
        return $this->responseSuccess($transOrder);
    }

    public function listTransOrderWorker(Request $request)
    {
        $query = $this->repository->getModel()::query();
        $user = $request->user();
        $employee_id = EmployeeView::where('partner_id', $user->partner_id)->first()->id;
        $resource_type_ids = ResourceType::where('group', 'Công nhân')->get()->pluck('id')->toArray();
        $query = QueryBuilder::for($query, $request)->allowedFilters(
            'code',
            'tr_req_id',
            'type_id',
            'from_id',
            'destination_id',
            'start_date',
            'start_time',
            'end_date',
            'end_time',
            'shift_id',
            'created_by_id',
            'status_id',
            'updated_by_id',
            'created_at'
        )
            ->orderBy('created_at', 'desc')
            ->with(['transOrderLocation.transOrderResource', 'transOrderLocation.transOrderResource.refusalReason', 'transOrderLocation.pickupAddress', 'transOrderLocation.pickupAddress.parent', 'shift'])
            ->whereHas('transOrderLocation.transOrderResource', function ($q) use ($employee_id, $resource_type_ids) {
                $q->whereIn('resource_type_id', $resource_type_ids);
                $q->where('resource_id', $employee_id);
            });
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function showOrderLeader(Request $request, $id)
    {
        $user = $request->user();
        $employee = EmployeeView::where('partner_id', $user->partner_id)->first();
        $resource_type_ids = ResourceType::where('group', 'Công nhân')->get()->pluck('id')->toArray();
        $transOrder = FleetTransportOrder::where('id', $id)
            ->with([
                'transOrderLocation.transOrderResource' => function ($query) use ($employee) {
                    $query->whereHas('employee', function ($q) use ($employee) {
                        $q->where('department_name', $employee->department_name ?? null);
                    });
                }, 'transOrderLocation.pickupAddress', 'transOrderLocation.pickupAddress.parent', 'shift',
            ])
            ->whereHas('transOrderLocation.transOrderResource', function ($q) use ($resource_type_ids) {
                $q->whereIn('resource_type_id', $resource_type_ids);
            })
            ->first();
        return $this->responseSuccess($transOrder);
    }
    public function addWorkerToTransOrder(Request $request, $id)
    {
        $request->validate([
            'detail_id' => [
                'required',

            ],
            'resource_type_id' => ['required', 'exists:res_resource_types,id'],
            'resource_ids' => ['required', 'array'],
        ]);
        $info = $request->only(['detail_id', 'resource_type_id', 'resource_ids']);
        try {
            $model_resources = [];
            $resource_ids = $info['resource_ids'];
            DB::beginTransaction();
            $model = ResourceType::where('id', $info['resource_type_id'])->first();
            $reqLocation = FleetTranOrderLocation::where('id', $info['detail_id'])->with(['detail.detailResources' => function ($query) use ($model) {
                $query->where('resource_type_id', $model->id);
            }]
            )->first()->toArray();
            $req_detail_resource_id = $reqLocation['detail']['detail_resources'][0]['id'];
            foreach ($resource_ids as $resource_id) {
                $model_resource = FleetTranOrderResource::updateOrCreate([
                    'fleet_tran_order_location_id' => $info['detail_id'],
                    'resource_type_id' => $info['resource_type_id'],
                    'resource_id' => $resource_id,
                    'req_detail_resource_id' => $req_detail_resource_id ?? null,
                ]);
                $model_resources[] = $model_resource;
            }
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $req_detail_resource_id)->count();
            $detail_req = FleetReqDetailResource::findOrFail($req_detail_resource_id);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            if ($count_number_of_resource_assigned >= $detail_req->number_of_resources_required) {
                $detail_req->state = '2-Đã đủ yêu cầu';
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
    public function removeWorker(Request $request, $resource_id)
    {
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
    public function removeWorkers(Request $request)
    {
        $resource_ids = $request->get('ids');
        return DB::transaction(function () use ($resource_ids) {
            $model = FleetTranOrderResource::findOrFail($resource_ids[0]);
            FleetTranOrderResource::whereIn('id', $resource_ids)->delete();
            $count_number_of_resource_assigned = FleetTranOrderResource::where('req_detail_resource_id', $model->req_detail_resource_id)->count();
            $detail_req = FleetReqDetailResource::findOrFail($model->req_detail_resource_id);
            $detail_req->number_of_resource_assigned = $count_number_of_resource_assigned;
            $detail_req->save();
            return $this->responseSuccess(['detail' => $detail_req]);
        });
    }
}
