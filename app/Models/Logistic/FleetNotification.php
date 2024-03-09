<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetNotification extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'tran_order_id',
        'tran_order_location_id',
        'tran_order_resource_id',
        'resource_id',
        'resource_type_id',
        'message',
        'status',
        'user_id',
        'time_notification',
    ];
}
