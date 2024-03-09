<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use App\Models\HR\Employee;
use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicle extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle';
    protected $table = 'fleet_vehicles';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'license_plate',
        'state_id',
        'location',
        'weight',
        'weight_unit_id',
        'max_volume',
        'min_volume',
        'volume_unit_id',
        'model_id',
        'chassis_number',
        'manufacture_year',
        'color',
        'fuel_type',
        'insurance_term',
        'registration_deadline',
        'acquisition_date',
        'odometer',
        'driver_id',
        'ownership',
        'has_image',
        'image',
        'image_medium',
        'image_small',
        'vehicle_registration_name',
        'vehicle_owner_id',
        'active',
    ];

    public function state()
    {
        return $this->belongsTo(FleetVehicleState::class, 'state_id', 'id');
    }
    public function weightUnit()
    {
        return $this->belongsTo(Unit::class, 'weight_unit_id');
    }
    public function volumeUnit()
    {
        return $this->belongsTo(Unit::class, 'volume_unit_id');
    }
    public function fleetVehicleModel()
    {
        return $this->belongsTo(FleetVehicleModel::class, 'model_id');
    }
    public function driver()
    {
        return $this->belongsTo(Employee::class, 'driver_id');
    }
    public function vehicle_owner()
    {
        return $this->belongsTo(Partner::class, 'vehicle_owner_id');
    }
}
