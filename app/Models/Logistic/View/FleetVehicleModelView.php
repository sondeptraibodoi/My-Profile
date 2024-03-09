<?php

namespace App\Models\Logistic\View;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetVehicleModelView extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle_model';
    protected $table = 'materialized_fleet_vehicle_models_view';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'short_name',
        'name',
        'description',
        'type_of_transport_id',
        'weight',
        'weight_unit_id',
        'volume',
        'volume_unit_id',
        'vehicle_manufacturers_id',
        'has_image',
        'image',
        'image_medium',
        'image_small',
        'active',
    ];
}
