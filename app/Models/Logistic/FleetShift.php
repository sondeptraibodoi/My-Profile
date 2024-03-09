<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class FleetShift extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_shifts';
    protected $table = 'fleet_shifts';
    protected $fillable = [
        'code',
        'name',
        'start_time',
        'end_time',
        'earliest_request_time',
        'latest_request_time',
        'description',
    ];

    //Thêm atb ảo
    protected $appends = ['active'];

     public function getActiveAttribute()
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startTime = Carbon::createFromTimeString($this->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::createFromTimeString($this->end_time, 'Asia/Ho_Chi_Minh');

        return $now->between($startTime, $endTime);
    }

    public function transportationRequests()
    {
        return $this->hasMany(FleetTransportationRequest::class, 'shift_id');
    }

    
}
