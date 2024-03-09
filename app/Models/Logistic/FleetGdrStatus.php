<?php

namespace App\Models\Logistic;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetGdrStatus extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_gdr_statuses';
    protected $table = 'fleet_gdr_statuses';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'next_status_id',
        'active',
    ];
    public function next()
    {
        return $this->belongsTo(FleetReqStatus::class, 'next_status_id')->where('active', true);
    }
}
