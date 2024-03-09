<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicleBrand extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle_brand';
    protected $table = 'fleet_vehicle_brands';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
    ];
}
