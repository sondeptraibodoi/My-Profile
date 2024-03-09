<?php

namespace App\Models\Logistic;

use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

class FleetTransportationRequest extends BaseModel
{
    use HasFactory;
    public static $INCLUDE = ['status', 'type', 'shift', 'locations', 'locations.detailResources.resourceConditions', 'createdBy', 'updatedBy', 'from', 'details', 'details.address', 'details.detailResources.resourceConditions.condition', 'details.detailResources.resourceType', 'details.detailResources.resourceType.model'];
    public static $FILTER = [
        'type_id',
        'from_id',
        'destination_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'shift_id',
        'status_id',
    ];
    protected $fillable = [
        'name',
        'type_id',
        'from_id',
        'destination_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'shift_id',
        'priority',
        'status_id',
        'notes',
        'created_by_id',
        'updated_by_id',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function status()
    {
        return $this->belongsTo(FleetReqStatus::class, 'status_id');
    }
    public function type()
    {
        return $this->belongsTo(FleetReqType::class, 'type_id');
    }
    public function shift()
    {
        return $this->belongsTo(FleetShift::class, 'shift_id');
    }
    public function order()
    {
        return $this->hasOne(FleetTransportOrder::class, 'tr_req_id');
    }
    public function locations()
    {
        return $this->hasMany(FleetReqDetailLocation::class, 'tr_req_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
    public function from()
    {
        return $this->belongsTo(Partner::class, 'from_id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
    public function details()
    {
        return $this->hasMany(FleetReqDetailLocation::class, 'tr_req_id');
    }
    public static function validate($type, $info = [])
    {
        if ($type === 'create') {
            $validator = Validator::make($info, [
                'type_id' => ['required'],
            ], [], []);
        }

        if (isset($validator)) {
            $validator->validate();
        }

        if ($type === 'create') {
            if (empty($info['dia_chis'])) {
                abort(400, 'Yêu cầu vận chuyển cần chọn địa chỉ');
            }
        }
    }
}
