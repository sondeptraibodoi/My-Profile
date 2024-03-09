<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Controller;
use App\Models\Logistic\FleetTranOrderResource;
use App\Models\System\ResourceType;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceTypeStatisticalController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['group' => [
            'required',
            Rule::exists('res_resource_types', 'group'),
        ]]);
        $data_return = collect();
        $group = $request->get('group');
        $vehicle_name = $request->get('vehical_name');
        switch ($request->get('group')) {
            case 'Phương tiện':
                $query = DB::table('resource_materialized_view_phuong_tien')->groupBy(['state_name', 'state_color'])->select(['state_name', 'state_color', DB::raw('count(*) as count'), DB::raw("string_agg(license_plate, ',') as license_plates")]);
                if (!empty($vehicle_name)) {
                    $query->where('type_name', $vehicle_name);
                }
                $data_return = $query->get();
                break;

            default:
                # code...
                break;
        }
        $resource_types = ResourceType::where('group', $group)->active()->get();
        $query = FleetTranOrderResource::query();
        $query->whereHas("fleetTranOrderLocations.transportOrder", function ($query) {
            $query->inDate(Carbon::now()->format('Y-m-d'));
        });
        $query->whereIn('resource_type_id', $resource_types->pluck('id'));
        $query_trung_lich = clone $query;
        $data_trung_lich = $query_trung_lich->groupBy('resource_id')
            ->having(DB::raw('count(resource_id)'), '>', 1)
            ->get(['resource_id', DB::raw('count(resource_id) as count')]);
        $data_ids = $data_trung_lich->pluck('resource_id');
        $resources = DB::table('resource_materialized_view_phuong_tien')->whereIn('id', $data_ids)->get()->mapWithKeys(function ($item) {
            return [$item->id => $item];
        });
        $data_return->prepend([
            'state_name' => 'Trùng lịch',
            'state_color' => 'warning',
            'count' => $data_trung_lich->count(),
            'resources' => $data_trung_lich->map(function ($item) use ($resources) {
                $item['resource'] = $resources[$item->resource_id] ?? (object) [];
                return $item;
            })
        ]);
        return $data_return;
    }
}
