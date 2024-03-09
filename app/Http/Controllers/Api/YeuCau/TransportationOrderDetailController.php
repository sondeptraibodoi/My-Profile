<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Helpers\Dynamic\TableHelper;
use App\Http\Controllers\Api\BaseController;
use App\Models\Logistic\FleetTranOrderLocation;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class TransportationOrderDetailController extends BaseController
{

    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTranOrderLocation::class);
        $this->extra_list = function ($query, $request) {
            $user = $request->user();
            $user_group = DB::table('res_user_groups')->where('user_id', '=', $user->id)->get();
            // $roles = [];
            // if (count($user_group) > 0) {
            //     foreach ($user_group as $user) {
            //         $roles[] = DB::table('res_groups')->find($user->group_id)->name;
            //     }
            // }

            $query->whereHas('transportOrder', function ($query) use ($request) {
                if ($request->has('shift_id')) {
                    $query->where('shift_id', $request->get('shift_id'));
                }
                if ($request->has('transportation_date')) {
                    $date_1 = $request->get('transportation_date')[0];
                    $date_2 = $request->get('transportation_date')[1];

                    // if (empty($transportation_date)) {
                    //     $query->where('id', 0);
                    // }
                    $query->inDateRange($date_1, $date_2);
                } else if (!$request->has('transportation_date')) {
                    $query->inDate(Carbon::now());
                }
            });

            if ($request->has('territory_id')) {
                $query->whereHas('address.parent.customers', function ($query) use ($request) {
                    $query->where('territory_id', $request->get('territory_id'));
                });
            };
            if ($request->has('resource_type_id')) {
                $query->whereHas('detail.detailResources.resourceType', function ($query) use ($request) {
                    $query->where('id', $request->get('resource_type_id'));
                });
            };
            if ($request->has('customer_id')) {
                $query->whereHas('address', function ($query) use ($request) {
                    $query->where('parent_id', $request->get('customer_id'));
                });
            };
            if ($request->has('created_by_id') && $user_group) {
                $query->whereHas('detail.transportationRequest', function ($query) use ($request) {
                    $query->whereIn('created_by_id', $request->get('created_by_id'));
                });
            }
            if (!$request->has('created_by_id')) {
                $query->whereHas('detail.transportationRequest', function ($query) use ($user) {
                    $query->where('created_by_id', $user->id);
                });
            }
            if ($request->boolean('withCreatedBy')) {
                $query->with(['detail.transportationRequest.createdBy']);
            }
            if ($request->boolean('withRequest')) {
                $group_resource_type = $request->get('group_resource_type');
                $query->with(['address:id,name,contact_address,parent_id', 'transportOrder.request', 'detail.detailResources' => function ($query) use ($group_resource_type) {
                    if (!empty($group_resource_type)) {
                        $query->whereHas('resourceType', function ($query) use ($group_resource_type) {
                            $query->where('group', $group_resource_type);
                        });
                    }
                }, 'detail.detailResources.resourceType' => function ($query) {
                    $query->orderBy('id');
                }, 'detail.detailResources.resourceType.model', 'detail.detailResources.resourceConditions.condition', 'transportOrder.shift']);
                $query->has('detail.detailResources');

                $query->whereHas('detail.detailResources', function ($query) use ($group_resource_type) {
                    if (!empty($group_resource_type)) {
                        $query->whereHas('resourceType', function ($query) use ($group_resource_type) {
                            $query->where('group', $group_resource_type);
                        });
                    }
                }, '>=', 1);

                $query->whereNotNull('req_detail_location_id');
            };
        };
        $this->extra_show = function ($query, Request $request) {
            if ($request->boolean('withCreatedBy')) {
                $query->with(['detail.transportationRequest.createdBy']);
            }
            if ($request->boolean('withResource')) {
                $group_resource_type = $request->get('group_resource_type');
                $query->with(['address:id,name,contact_address,parent_id', 'transportOrder.request', 'detail.detailResources' => function ($query) use ($group_resource_type) {
                    if (!empty($group_resource_type)) {
                        $query->whereHas('resourceType', function ($query) use ($group_resource_type) {
                            $query->where('group', $group_resource_type);
                        });
                    }
                }, 'detail.detailResources.resourceType' => function ($query) {
                    $query->orderBy('id');
                }, 'detail.detailResources.resourceConditions.condition', 'transportOrder.shift', 'resources']);
            };
        };
        $this->extra_after_show = function ($data, Request $request) {
            $data->load('address:id,name,contact_address,parent_id', 'resources.resourceType.model', 'resources.reasonForRefusal');
            $item_info = $data->toArray();
            if ($request->boolean('withResource')) {
                $cache = [];
                foreach ($item_info['resources'] as $index_resource => $resource) {
                    if (empty($cache[$resource['resource_type']['model_id']])) {
                        $cache[$resource['resource_type']['model_id']] = [];
                    }
                    $cache[$resource['resource_type']['model_id']][$resource['resource_id']] = [
                        'req_detail_resource_id' => $resource['req_detail_resource_id'],
                        'resource' => $resource,
                    ];
                }
                $item_info['detail_resources'] = [];
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
                                if (empty($item_info['detail_resources'][$tmp['req_detail_resource_id']])) {
                                    $item_info['detail_resources'][$tmp['req_detail_resource_id']] = [];
                                }
                                $tmp_resource = $tmp['resource'];
                                $tmp_resource['resource'] = $item;
                                $item_info['detail_resources'][$tmp['req_detail_resource_id']][] = $tmp_resource;
                            }
                        }
                    }
                }
            }
            return $item_info;
        };
    }
}
