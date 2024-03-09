<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetDeliveryOrder extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_delivery_order';
    protected $table = 'fleet_delivery_orders';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'fleet_to_resource_id',
        'from_id',
        'start_date',
        'start_time',
        'pickup_date',
        'pickup_time',
        'break_time',
        'end_of_delivery_date',
        'end_of_delivery_time',
        'destination_id',
        'end_date',
        'end_time',
        'notes',
        'state',
    ];
    public function transOrderResource()
    {
        return $this->belongsTo(FleetTranOrderResource::class, 'fleet_to_resource_id');
    }
}
