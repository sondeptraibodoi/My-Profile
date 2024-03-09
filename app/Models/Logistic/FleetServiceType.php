<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetServiceType extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_service_type';
    protected $table = 'fleet_service_types';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'category',
        'priority',
    ];
}
