<?php

namespace App\Models\BA\BBGN;

use App\Models\BaseModel;
use App\Models\IM\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetGdrLineModel extends BaseModel
{
    use HasFactory;

    const LOG_NAME = 'fleet_gdr_lines';
    protected $table = 'fleet_gdr_lines';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'gdr_id',
        'item_id',
        'name',
        'production_unit_price',
        'production_quantity',
        'production_amount',
        'purchasing_unit_price',
        'purchasing_quantity',
        'purchasing_amount',
        'sub_total',
    ];

    public function gdr()
    {
        return $this->belongsTo(FleetGoodDeliveryReceipt::class, 'gdr_id');
    }

    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id');
    }
}
