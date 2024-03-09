<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetReqType extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'description',
        'priority',
        'active',
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
    public function transportationRequests()
    {
        return $this->hasMany(FleetTransportationRequest::class, 'type_id');
    }
}
