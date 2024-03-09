<?php

namespace App\Models\Logistic;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetRefusalReason extends Model
{
    use HasFactory;
    const LOG_NAME = 'fleet_refusal_reason';
    protected $table = 'fleet_refusal_reasons';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'resource_type_id',
        'name',
        'description',
        'active',

    ];
}
