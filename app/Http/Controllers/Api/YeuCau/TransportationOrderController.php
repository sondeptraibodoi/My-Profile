<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Helpers\Dynamic\TableHelper;
use App\Http\Controllers\Api\BaseController;
use App\Models\Logistic\FleetTransportOrder;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class TransportationOrderController extends BaseController
{

    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTransportOrder::class);
        $this->extra_list = function ($query, $request) {
            $transportation_date = $request->get('transportation_date');
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
                $query->inDate($transportation_date);
            };
        };
        $this->extra_after_show = function ($data, Request $request) {
            $item_info = $data->toArray();
            if ($request->boolean('withResource')) {
                $data->load('details.address:id,name,contact_address,parent_id', 'details.resources.resourceType.model', 'details.resources.reasonForRefusal');
                $cache = [];
                foreach ($data['details'] as $index_detail => $detail) {
                    foreach ($detail['resources'] as $index_resource => $resource) {
                        if (empty($cache[$resource->resourceType->model_id])) {
                            $cache[$resource->resourceType->model_id] = [];
                        }
                        $cache[$resource->resourceType->model_id][$resource->resource_id] = [
                            'index_detail' => $index_detail,
                            'index_resource' => $index_resource,
                        ];
                    }
                }
                $items_details = $data['details']->toArray();
                foreach ($cache as $resource_type_id => $values) {
                    $ids = array_keys($values);
                    $table = TableHelper::getTable($resource_type_id, false);
                    if (isset($table)) {
                        $query = TableHelper::getQuery($table, $table->code);
                        $query->whereIn('id', $ids);
                        $items = $query->get();
                        foreach ($items as $item) {
                            $tmp = $values[$item->id];
                            if (isset($tmp)) {
                                $item = $item->toArray();
                                unset($item['image']);
                                unset($item['image_medium']);
                                unset($item['image_small']);
                                $detail = $items_details[$tmp['index_detail']];
                                $resources = $detail['resources'];
                                $resource = $resources[$tmp['index_resource']];
                                $resource['resource'] = $item;
                                $resources[$tmp['index_resource']] = $resource;
                                $detail['resources'] = $resources;
                                $items_details[$tmp['index_detail']] = $detail;
                            }
                        }
                    }
                }
                $item_info['details'] = $items_details;
            }
            return $item_info;
        };
    }
    public function updateStatus(Request $request, $id)
    {
        $info = $request->only(['status_id']);
        $data = $this->repository->update($id, $info);
        return $this->responseSuccess($data);
    }
    public function updateShift(Request $request, $id)
    {
        $info = $request->only(['shift_id']);
        $data = $this->repository->update($id, $info);
        return $this->responseSuccess($data);
    }
}
