<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetReqStatus extends BaseModel
{
    use HasFactory;
    public static $FILTER = ['active'];
    public static $INCLUDE = ['next', 'prev', 'prevs'];
    public static $SEARCH = ['name', 'description'];
    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
        'next_status_id',
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
    public function next()
    {
        return $this->belongsTo(FleetReqStatus::class, 'next_status_id')->where('active', true);
    }
    public function prev()
    {
        return $this->hasOne(FleetReqStatus::class, 'next_status_id')->where('active', true);
    }
    public function prevs()
    {
        return $this->hasMany(FleetReqStatus::class, 'next_status_id')->where('active', true);
    }
    public function transportationRequests()
    {
        return $this->hasMany(FleetTransportationRequest::class, 'status_id');
    }
}
