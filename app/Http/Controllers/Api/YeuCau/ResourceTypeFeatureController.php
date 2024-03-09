<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Helpers\Dynamic\TableHelper;
use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\Logistic\FleetReqDetailResource;
use DB;
use Illuminate\Http\Request;

class ResourceTypeFeatureController extends Controller
{

    public function index(Request $request, $id)
    {
        $detail = FleetReqDetailResource::findOrFail($id);
        $detail->load(['resourceType', 'resourceConditions']);
        $table = TableHelper::getTable($detail->resourceType->model_id);
        $query = TableHelper::getQuery($table);
        $query = QueryBuilder::for($query, $request)
            ->initFromModelForList()
            ->allowedFilters(['state_name', 'department_id'])
            ->allowedPagination();
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function indexGroup(Request $request, $id)
    {
        $request->validate(['group_bys' => ['required', 'array']]);
        $group_bys = $request->group_bys;
        $detail = FleetReqDetailResource::findOrFail($id);
        $detail->load(['resourceType']);
        $table = TableHelper::getTable($detail->resourceType->model_id);
        $query = TableHelper::getQuery($table);
        $query = QueryBuilder::for($query, $request);
        $query->groupBy($group_bys);
        $query->select($group_bys);
        $query->addSelect(DB::raw('count(*) as count'));

        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
}
