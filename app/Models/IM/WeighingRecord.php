<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeighingRecord extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_weighing_record';
    protected $table = 'im_weighing_records';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'weighing_scale_id',
        'weighing_type',
        'weighing_date',
        'weighing_time_1',
        'weighing_time_2',
        'weighing_ticket_number',
        'vehicle_license_plate',
        'goods_type',
        'weighing_weight_1',
        'weighing_weight_2',
        'goods_weight',
        'buyer',
        'seller',
    ];
}
