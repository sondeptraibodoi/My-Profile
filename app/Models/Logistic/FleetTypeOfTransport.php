<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetTypeOfTransport extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_type_of_transport';
    protected $table = 'fleet_type_of_transports';
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

    public function fleet_vehicle_models()
    {
        return $this->hasMany(FleetVehicleModel::class, 'type_of_transport_id');
    }
}
