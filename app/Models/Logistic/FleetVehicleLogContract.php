<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicleLogContract extends BaseModel
{
    use HasFactory;
    const LOG_NAME = "fleet_vehicle_log_contract";
    protected $table = "fleet_vehicle_log_contracts";
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        "start_date",
        "expiration_date",
        "days_left",
        "insurer_id",
        "purchaser_id",
        "ins_ref",
        "state",
        "notes",
        "cost_generated",
        "cost_frequency",
        "cost_id",
        "sum_cost",
        "cost_amount",
    ];
}
