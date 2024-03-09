<?php

namespace App\Models\Logistic;

use App\Models\System\ResourceCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FleetReqResourceCondition extends Model
{
    use HasFactory;
    protected $fillable = [
        'tr_req_detail_id',
        'condition_id',
        'value',
        'fulfilled_value',
        'state',
        'notes',
    ];
    public function detailResource()
    {
        return $this->belongsTo(FleetReqDetailResource::class, 'tr_req_detail_id');
    }
    public function condition()
    {
        return $this->belongsTo(ResourceCondition::class, 'condition_id');
    }
}
