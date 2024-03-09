<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetTransportOrder extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_transport_order';
    protected $table = 'fleet_transport_orders';
    protected $guard = [
        'id',
        'created_at',
    ];
    protected $fillable = [
        'code',
        'tr_req_id',
        'type_id',
        'from_id',
        'destination_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'shift_id',
        'created_by_id',
        'created_at',
        'status_id',
        'notes',
        'updated_at',
        'updated_by_id',
        'is_deleted',
    ];

    public function transOrderLocation()
    {
        return $this->hasMany(FleetTranOrderLocation::class, 'delivery_order_id');
    }
    public function shift()
    {
        return $this->hasOne(FleetShift::class, 'id', 'shift_id');
    }
}
