<?php

namespace App\Models\Logistic;

use App\Casts\TimeCast;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetTranOrderLocation extends Model
{
    use HasFactory;
    public static $INCLUDE = ['address', 'transportOrder', 'transportOrder.request'];
    public static $SORT = ['created_at', 'start_time'];
    protected $table = 'fleet_tran_order_locations';
    protected $hidden = ['updated_at', 'created_at'];
    protected $fillable = [
        'req_detail_resource_id',
        'code',
        'delivery_order_id',
        'destination_id',
        'start_date',
        'start_time',
        'earliest_pickup_time',
        'latest_pickup_time',
        'expected_exchange_time',
        'break_time',
        'end_date',
        'end_time',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'end_time' => TimeCast::class,
        'start_time' => TimeCast::class,
        'earliest_pickup_time' => TimeCast::class,
        'latest_pickup_time' => TimeCast::class,
    ];
    public function transportOrder()
    {
        return $this->belongsTo(FleetTransportOrder::class, 'delivery_order_id');
    }
    public function transOrderResource()
    {
        return $this->hasMany(FleetTranOrderResource::class, 'fleet_tran_order_location_id');
    }
    public function pickupAddress()
    {
        return $this->hasOne(Partner::class, 'id', 'destination_id');
    }
    public function detail()
    {
        return $this->belongsTo(FleetReqDetailLocation::class, 'req_detail_location_id');
    }
}
