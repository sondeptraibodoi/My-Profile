<?php

namespace App\Models\Logistic;

use App\Models\HR\EmployeeView;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetTranOrderResource extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_tran_order_resources';
    protected $table = 'fleet_tran_order_resources';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'fleet_tran_order_location_id',
        'resource_type_id',
        'resource_id',
        'acceptance',
        'reason_for_refusal_id',
        'detail_of_reason',
        'notes',
        "req_detail_resource_id",
    ];
    public function transOrderLocation()
    {
        return $this->belongsTo(FleetTranOrderLocation::class, 'fleet_tran_order_location_id');
    }
    public function refusalReason()
    {
        return $this->belongsTo(FleetRefusalReason::class, 'reason_for_refusal_id');
    }
    public function fleetTranOrderLocations()
    {
        return $this->belongsTo(FleetTranOrderLocation::class, 'fleet_tran_order_location_id');
    }
    public function fleetReqTranOrderResource()
    {
        return $this->belongsTo(FleetReqDetailResource::class, 'req_detail_resource_id');
    }
    public function employee()
    {
        return $this->hasOne(EmployeeView::class, 'id', 'resource_id');
    }
}
