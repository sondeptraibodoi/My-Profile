<?php

namespace App\Models\Logistic\View;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicleView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle_view';
    protected $table = 'materialized_fleet_vehicles_view';

}
