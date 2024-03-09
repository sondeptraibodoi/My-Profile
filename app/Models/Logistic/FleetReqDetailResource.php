<?php

namespace App\Models\Logistic;

use App\Models\System\ResourceType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetReqDetailResource extends Model
{
    use HasFactory;
    protected $fillable = [
        'tr_req_detail_location_id',
        'resource_type_id',
        'notes',
        'number_of_resources_required',
        'number_of_resources_assigned'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::deleting(function ($model) {
            $model->resourceConditions()->delete();
        });
    }

    public function detailLocation()
    {
        return $this->belongsTo(FleetReqDetailLocation::class, 'tr_req_detail_location_id');
    }
    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }
    public function resourceConditions()
    {
        return $this->hasMany(FleetReqResourceCondition::class, 'tr_req_detail_id');
    }
}
