<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use App\Models\BA\BBGN\FleetGoodDeliveryReceipt;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetDoNotification extends BaseModel
{
    use HasFactory;

    const LOG_NAME = 'fleet_do_notifications';
    protected $table = 'fleet_do_notifications';
    protected $fillable = [
        'fleet_delivery_order_location_id',
        'partner_id',
        'fleet_good_delivery_receipt_id',
        'notification_date',
        'state',
        'req_location_id',
        'req_date',
        'tran_order_location_id',
        'tran_order_date',
        'shift_id',
        'destination_id',
        'fleet_vehicle_id',
    ];
    protected $appends = ['stateBBGN'];

    public function fleetDeliveryOrderLocation()
    {
        return $this->belongsTo(FleetDeliveryOrder::class, 'fleet_delivery_order_location_id');
    }
    public function fleetGoodDeliveryReceipt()
    {
        return $this->belongsTo(FleetGoodDeliveryReceipt::class, 'fleet_good_delivery_receipt_id');
    }
    public function getStateBBGNAttribute()
    {
        return $this->partner ? 'BBGN trống' : 'Đã nhập BBGN';
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function shift()
    {
        return $this->belongsTo(FleetShift::class, 'shift_id');
    }
    public function destination()
    {
        return $this->belongsTo(Partner::class, 'destination_id');
    }
    public function fleetVehicle()
    {
        return $this->belongsTo(FleetVehicle::class, 'fleet_vehicle_id');
    }
}
