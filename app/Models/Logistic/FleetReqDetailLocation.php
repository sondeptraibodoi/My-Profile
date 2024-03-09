<?php

namespace App\Models\Logistic;

use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetReqDetailLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'tr_req_id',
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
        'notes2',
        'notes3',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function transportationRequest()
    {
        return $this->belongsTo(FleetTransportationRequest::class, 'tr_req_id');
    }
    public function address()
    {
        return $this->belongsTo(Partner::class, 'destination_id');
    }
    public function detailResources()
    {
        return $this->hasMany(FleetReqDetailResource::class, 'tr_req_detail_location_id');
    }
}
