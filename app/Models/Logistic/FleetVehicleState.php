<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicleState extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle_state';
    protected $table = 'fleet_vehicle_states';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'priority',
    ];
}
