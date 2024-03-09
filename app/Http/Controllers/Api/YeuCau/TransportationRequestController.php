<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Models\Logistic\FleetReqDetailLocation;
use App\Models\Logistic\FleetReqDetailResource;
use App\Models\Logistic\FleetReqResourceCondition;
use App\Models\Logistic\FleetReqStatus;
use App\Models\Logistic\FleetReqType;
use App\Models\Logistic\FleetTranOrderLocation;
use App\Models\Logistic\FleetTranOrderStatus;
use App\Models\Logistic\FleetTranOrderType;
use App\Models\Logistic\FleetTransportationRequest;
use App\Models\Logistic\FleetTransportOrder;
use App\Models\Res\Partner;
use App\Repositories\BaseRepository;
use App\Traits\SendNotification;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class TransportationRequestController extends BaseController
{
    use SendNotification;
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTransportationRequest::class);
        $this->extra_list = function ($query, $request) {
            $transportation_date = $request->get('transportation_date');
            $created_by_id = $request->get('created_by_id');
            // if (empty($transportation_date)) {
            //     $query->where('id', 0);
            // }
            $query->allowedFilters(
                'type_id',
                'from_id',
                'destination_id',
                'start_date',
                'start_time',
                'end_date',
                'end_time',
                'shift_id',
                'status_id',
            );
            if (!empty($transportation_date)) {
                $query->where(function ($query) use ($transportation_date) {
                    $query->whereNull('start_date');
                    $query->orWhere('start_date', $transportation_date);
                });
            };
            if (!empty($created_by_id)) {
                $query->whereIn('created_by_id', $created_by_id);
            };
            if ($request->boolean('withDetail')) {
                $query->with(['details.address:id,name,contact_address,parent_id', 'details.detailResources.resourceType' => function ($query) {
                    $query->orderBy('group');
                }, 'details.detailResources.resourceType.conditions.operator', 'details.detailResources.resourceConditions.condition']);
            };
            if ($request->has('territory_id')) {
                $query->whereHas('details.address.parent.customers', function ($query) use ($request) {
                    $query->where('territory_id', $request->get('territory_id'));
                });
            };
        };
        $this->extra_update = function ($model, $old, $attributes) {
            $dia_chis = $attributes['dia_chis'];
            $dia_chi_ids = [];
            foreach ($dia_chis as $dia_chi) {
                $resource_type_ids = [];
                $resources = $dia_chi['resources'];
                unset($dia_chi['resources']);
                if (!empty($dia_chi['address_id'])) {
                    $dia_chi['destination_id'] = $dia_chi['address_id'];
                    $dia_chi_ids[] = $dia_chi['address_id'];
                }
                unset($dia_chi['address_id']);
                $detail = FleetReqDetailLocation::updateOrCreate(['tr_req_id' => $model->getKey(), 'destination_id' => $dia_chi['destination_id']], $dia_chi);

                foreach ($resources as $resource) {
                    $resource_type_ids[] = $resource['type_id'];
                    $condition_ids = [];
                    $detail_location = FleetReqDetailResource::updateOrCreate([
                        'tr_req_detail_location_id' => $detail->getKey(),
                        'resource_type_id' => $resource['type_id'],
                    ], [
                        'number_of_resources_required' => $resource['count'],
                    ]);
                    foreach ($resource['conditions'] as $item) {
                        if (!empty($item['value'])) {
                            $condition_ids[] = $item['condition_id'];
                            FleetReqResourceCondition::updateOrCreate([
                                'tr_req_detail_id' => $detail_location->getKey(),
                                'condition_id' => $item['condition_id'],
                            ], [
                                'value' => $item['value'],
                            ]);
                        }
                    }
                    FleetReqResourceCondition::where('tr_req_detail_id', $detail_location->getKey())->whereNotIn('condition_id', $condition_ids)->delete();
                }
                //delete condition not in
                FleetReqResourceCondition::whereHas('detailResource', function ($query) use ($detail, $resource_type_ids) {
                    $query->where('tr_req_detail_location_id', $detail->getKey())->whereNotIn('resource_type_id', $resource_type_ids);
                })->delete();
                //delete detail not in
                FleetReqDetailResource::where('tr_req_detail_location_id', $detail->getKey())->whereNotIn('resource_type_id', $resource_type_ids)->delete();
            }
            //delete condition not in
            FleetReqResourceCondition::whereHas('detailResource.detailLocation', function ($query) use ($model, $dia_chi_ids) {
                $query->where('tr_req_id', $model->getKey())->whereNotIn('destination_id', $dia_chi_ids);
            })->delete();
            //delete detail not in
            FleetReqDetailResource::whereHas('detailLocation', function ($query) use ($model, $dia_chi_ids) {
                $query->where('tr_req_id', $model->getKey())->whereNotIn('destination_id', $dia_chi_ids);
            })->delete();
            //delete details not in
            FleetReqDetailLocation::where('tr_req_id', $model->getKey())->whereNotIn('destination_id', $dia_chi_ids)->delete();
            $request = request();
            $user = $request->user();
            $model->updated_by_id = $user->getKey();
            $companies = [];
            $default_name = 'Yêu cầu vận chuyển tới ';
            $req_name = null;
            $destinations = Partner::whereIn('id', $dia_chi_ids)->with('parent')->get();
            foreach ($destinations as $destination) {
                $customer = $destination->parent;
                if (!empty($customer)) {
                    if (count($companies) === 0) {
                        array_push($companies, $customer->getKey());

                        $req_name = $default_name . $customer->short_name;

                        continue;
                    };

                    if (!in_array($customer->getKey(), $companies)) {
                        array_push($companies, $customer->getKey());

                        $req_name = $req_name . ', ' . $customer->short_name;
                    }
                }
            }
            $type_request = $model->type;

            if ($req_name !== $default_name) {
                $req_name = $req_name . ' - ' . $type_request->name;
            } else {
                $req_name = $type_request->name;
            };
            $model->name = $req_name;
        };
        $this->extra_create = function ($model, $attributes) {
            $request = request();
            $user = $request->user();

            $dia_chis = $attributes['dia_chis'];
            $companies = [];
            $default_name = 'Yêu cầu vận chuyển tới ';
            $req_name = null;

            foreach ($dia_chis as $dia_chi) {
                $destination = Partner::findOrFail($dia_chi['address_id']);
                $customer = $destination->parent;
                if (!empty($customer)) {
                    if (count($companies) === 0) {
                        array_push($companies, $customer->getKey());

                        $req_name = $default_name . $customer->short_name;

                        continue;
                    };

                    if (!in_array($customer->getKey(), $companies)) {
                        array_push($companies, $customer->getKey());

                        $req_name = $req_name . ', ' . $customer->short_name;
                    }
                }
            }

            $type_request = FleetReqType::findOrFail($model->type_id);

            if ($req_name !== $default_name) {
                $req_name = $req_name . ' - ' . $type_request->name;
            } else {
                $req_name = $type_request->name;
            };

            $model->name = $req_name;
            $model->status_id = FleetReqStatus::isActive()->defaultOrder()->firstOrFail()->getKey();
            $model->created_by_id = $user->getKey();
        };
        $this->extra_after_create = function ($model, $attributes) {
            $dia_chis = $attributes['dia_chis'];
            foreach ($dia_chis as $dia_chi) {
                $resources = $dia_chi['resources'];
                unset($dia_chi['resources']);
                $dia_chi['destination_id'] = $dia_chi['address_id'];
                unset($dia_chi['address_id']);
                $detail = FleetReqDetailLocation::create(array_merge(['tr_req_id' => $model->getKey()], $dia_chi));

                foreach ($resources as $resource) {
                    $detail_location = FleetReqDetailResource::create([
                        'tr_req_detail_location_id' => $detail->getKey(),
                        'resource_type_id' => $resource['type_id'],
                        'number_of_resources_required' => $resource['count'],
                    ]);
                    foreach ($resource['conditions'] as $item) {
                        if (!empty($item['value'])) {
                            FleetReqResourceCondition::create([
                                'tr_req_detail_id' => $detail_location->getKey(),
                                'condition_id' => $item['condition_id'],
                                'value' => $item['value'],
                            ]);
                        }
                    }
                }
            }
        };

        $this->extra_delete = function ($model) {
            if (!empty($model->order)) {
                abort(400, 'Không thể xóa yêu cầu vận chuyển đã lên kế hoạch');
            };
            $model->load('locations.detailResources');
            $locations = $model->locations;
            foreach ($locations as $location) {
                $detail_resources = $location->detailResources;
                foreach ($detail_resources as $detail_resource) {
                    $detail_resource->delete();
                }
                $location->delete();
            }
        };

        $this->extra_after_show = function ($data, Request $request) {

            if ($request->boolean('withResourceConditions')) {
                $data->load(['details.address:id,name,contact_address,parent_id', 'details.detailResources.resourceType' => function ($query) {
                    $query->orderBy('group');
                }, 'details.detailResources.resourceType.conditions.operator', 'details.detailResources.resourceConditions.condition']);
            }
            return $data;
        };
    }
    public function updateStatus(Request $request, $id)
    {
        $this->authorize('update-model-feature', $this->repository->getModel());
        $user = $request->user();
        $info = $request->only(['status_id']);
        $info['updated_by_id'] = $user->getKey();
        $data = $this->repository->update($id, $info);
        return $this->responseSuccess($data);
    }
    public function updateShift(Request $request, $id)
    {
        $this->authorize('update-model-feature', $this->repository->getModel());
        $user = $request->user();
        $info = $request->only(['shift_id', 'start_date']);
        $info['updated_by_id'] = $user->getKey();
        $data = $this->repository->update($id, $info);
        return $this->responseSuccess($data);
    }
    // public function updateShifts(Request $request, $shift_id)
    // {
    //     $this->authorize('update-model-feature', $this->repository->getModel());
    //     $request->validate([
    //         'items' => ['array', 'required'],
    //         'items.*' => ['required'],
    //     ]);
    //     $info = $request->only('items');
    //     $items = $info['items'];
    //     $user_id = $request->user()->getKey();
    //     DB::beginTransaction();
    //     $request_status_id = FleetReqStatus::isActive()
    //         ->isComplete()
    //         ->defaultOrder()
    //         ->firstOrFail()
    //         ->getKey();

    //     $order_status_id = FleetTranOrderStatus::isActive()
    //         ->isCreate()
    //         ->defaultOrder()
    //         ->firstOrFail()
    //         ->getKey();

    //     $order_type_id = FleetTranOrderType::isActive()
    //         ->isRequest()
    //         ->defaultOrder()
    //         ->firstOrFail()
    //         ->getKey();

    //     $before_order = FleetTransportOrder::query()->orderBy('id', 'desc')->first();

    //     foreach ($items as $item) {
    //         //thay đổi trạng thái của request
    //         $tran_request = FleetTransportationRequest::findOrFail($item);
    //         $tran_request->status_id = $request_status_id;
    //         $tran_request->shift_id = $shift_id;
    //         $tran_request->updated_by_id = $user_id;
    //         $tran_request->save();

    //         $request_locations = $tran_request->locations;

    //         //tạo mới order từ request

    //         $tran_order = new FleetTransportOrder($tran_request->toArray());
    //         $tran_order->tr_req_id = $tran_request->id;
    //         $tran_order->status_id = $order_status_id;
    //         $tran_order->type_id = $order_type_id;
    //         $tran_order->created_by_id = $user_id;
    //         $tran_order->updated_by_id = null;

    //         if (!empty($before_order)) {
    //             $tran_order->code = 'TO-' . ($before_order->getKey() + 1);
    //         } else {
    //             $tran_order->code = 'TO-1';
    //         }

    //         if (empty($tran_request->start_date)) {
    //             $tran_order->start_date = Carbon::now();
    //         }
    //         if (empty($tran_request->shift_id)) {
    //             $tran_order->shift_id = $shift_id;
    //         }

    //         if (empty($tran_request->end_date)) {
    //             $tran_order->end_date = $tran_order->start_date;
    //         }

    //         $tran_order->save();
    //         $before_order = $tran_order;
    //         foreach ($request_locations as $request_location) {
    //             $order_location = new FleetTranOrderLocation($request_location->toArray());
    //             $order_location->delivery_order_id = $tran_order->getKey();
    //             $order_location->req_detail_location_id = $request_location->getKey();
    //             $order_location->save();
    //         }
    //     }
    //     DB::commit();
    //     return $this->responseSuccess();
    // }

    public function softDelete(Request $request, $id)
    {
        $this->authorize('delete-model-feature', $this->repository->getModel());
        $user = $request->user();
        $status_delete_id = FleetReqStatus::isActive()
            ->isDelete()
            ->defaultOrder()
            ->firstOrFail()
            ->getKey();
        $tran_request = $this->repository->find($id);
        if (!empty($tran_request->order)) {
            abort(400, 'Không thể xóa yêu cầu vận chuyển đã lên kế hoạch');
        };

        $tran_request->status_id = $status_delete_id;
        $tran_request->updated_by_id = $user->getKey();
        $tran_request->save();
        return $this->responseSuccess($tran_request);
    }

    public function restoreSoftDelete(Request $request, $id)
    {
        $this->authorize('update-model-feature', $this->repository->getModel());
        $user = $request->user();
        $status_create_id = FleetReqStatus::isActive()
            ->isCreate()
            ->defaultOrder()
            ->firstOrFail()
            ->getKey();
        $tran_request = $this->repository->find($id);

        $tran_request->status_id = $status_create_id;
        $tran_request->updated_by_id = $user->getKey();
        $tran_request->save();
        return $this->responseSuccess($tran_request);
    }

    public function updateTime(Request $request, $id)
    {
        $this->authorize('update-model-feature', FleetTranOrderLocation::class);
        $start_time = $request->input('start_time');
        $tranpost_request = FleetTranOrderLocation::find($id);
        $tranpost_request->start_time = $start_time;
        if (isset($tranpost_request->detail)) {
            $tranpost_request->detail->update(['start_time' => $start_time]);
        }
        $tranpost_request->save();
        return $this->responseSuccess($tranpost_request);
    }

    public function userCreateReq(Request $request)
    {
        $this->authorize('read-model-feature', $this->repository->getModel());
        $query = $this->repository->getModel()::query()->with(['createdBy']);
        $user_create_req = $query->distinct()->get(['created_by_id']);
        return $user_create_req->map(function ($item) {
            return $item['create_by'] ?? $item['createdBy'];
        });
    }
    public function updateDetailDieuKien(Request $request, $id)
    {
        $data = $request->all();
        $detail = FleetReqDetailResource::where('id', $id);
        $model = $detail->first();
        $model->fill($data);
        $model->save();

        // $model = ResourceType::where('id', $data['resource_type_id'])->first();
        // if ($model->group !== "VTCCDC") {
        //     $orderLocations = $detailResource['transportation_request']['order']['locations'];
        //     $location_id = null;
        //     foreach ($orderLocations as $location) {
        //         if ($location['destination_id'] == $detailResource['destination_id']) {
        //             $location_id = $location['id'];
        //         }
        //     }
        //     $orderResource = FleetTranOrderResource::where('fleet_tran_order_location_id', $location_id)
        //         ->where('resource_type_id', $data['resource_type_id'])->pluck('resource_id')->toArray();
        //     foreach ($orderResource as $resource_id) {
        //         $info = [
        //             "tran_order_location_id" => $location_id,
        //             "tran_order_id" => $detailResource['transportation_request']['order']['id'],
        //             "resource_id" => $resource_id,
        //         ];
        //         $message = $data['state'] !== '1-Chưa đáp ứng' ? MessageNotification::JOB_NEW : MessageNotification::JOB_CANCELLED;
        //         $this->sendNotificationToUser($model, $info, $message);
        //     }
        // }

        return $this->responseSuccess($model);
    }
    public function getLocationWarehouse()
    {
        $ware_house = DB::table('res_partner_category_references')
            ->join('res_partner_categories', 'res_partner_categories.id', '=', 'res_partner_category_references.partner_category_id')
            ->join('res_partners', 'res_partners.id', '=', 'res_partner_category_references.partner_id')
            ->where('res_partner_categories.name', '=', 'Địa điểm trả hàng')
            ->select('res_partners.name', 'res_partners.short_name', 'res_partner_categories.name as type', 'res_partners.id')->get();
        return $this->responseSuccess($ware_house);

    }
}
