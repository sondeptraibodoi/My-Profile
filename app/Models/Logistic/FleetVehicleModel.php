<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetVehicleModel extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_vehicle_model';
    protected $table = 'fleet_vehicle_models';
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

    public function fleetTypeOfTransport()
    {
        return $this->belongsTo(FleetTypeOfTransport::class, 'type_of_transport_id');
    }

    public function fleetVehicleBrand()
    {
        return $this->belongsTo(FleetVehicleBrand::class, 'vehicle_manufacturers_id');
    }
    public function weightUnit()
    {
        return $this->belongsTo(Unit::class, 'weight_unit_id');
    }
    public function volumeUnit()
    {
        return $this->belongsTo(Unit::class, 'volume_unit_id');
    }

    // public function getImageMediumAttribute($value)
    // {
    //     return $this->byteaToBase64($value);
    // }

    // public function getImageSmallAttribute($value)
    // {
    //     return $this->byteaToBase64($value);
    // }

    // public function byteaToBase64($value)
    // {
    //     $byteaData = stream_get_contents($value);
    //     $base64Image = base64_encode($byteaData);
    //     return $base64Image;
    // }
}
