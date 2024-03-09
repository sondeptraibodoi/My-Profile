<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

class InventoryLine extends BaseModel
{
    use HasFactory;
    protected $table = 'im_inventory_lines';
    public static $INCLUDE = ['inventory', 'location', 'product', 'unit', 'prodLot', 'product.product'];
    protected $fillable = [
        'inventory_id',
        'location_id',
        'product_id',
        'product_uom',
        'prod_lot_id',
        'product_qty',
    ];
    protected $guard = [
        'id',
        'product_qty',
    ];
    protected $casts = [
        'created_at'  => 'date',
    ];
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'product_uom');
    }
    public function prodLot()
    {
        return $this->belongsTo(ProductionLot::class, 'prod_lot_id');
    }
    public function scopeFromView($query)
    {
        return $query->from('im_inventory_lines_view');
    }
    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'inventory_id' => 'required|numeric',
                'location_id' => 'required|numeric',
                'product_id' => 'required|numeric',
                'product_uom' => 'required|numeric',
            ], [
                'inventory_id.required' => __('im_inventory_lines.required.inventory_id'),
                'location_id.required' => __('im_inventory_lines.required.location_id'),
                'product_id.required' => __('im_inventory_lines.required.product_id'),
                'product_uom.required' => __('im_inventory_lines.required.product_uom'),
            ], [
                'inventory_id' => __('im_inventory_lines.field.name'),
                'location_id' => __('im_inventory_lines.field.name'),
                'product_id' => __('im_inventory_lines.field.name'),
                'product_uom' => __('im_inventory_lines.field.name'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'inventory_id' => 'required|numeric',
                'location_id' => 'required|numeric',
                'product_id' => 'required|numeric',
                'product_uom' => 'required|numeric',
            ], [
                'inventory_id.required' => __('im_inventory_lines.required.inventory_id'),
                'location_id.required' => __('im_inventory_lines.required.location_id'),
                'product_id.required' => __('im_inventory_lines.required.product_id'),
                'product_uom.required' => __('im_inventory_lines.required.product_uom'),
            ], [
                'inventory_id' => __('im_inventory_lines.field.name'),
                'location_id' => __('im_inventory_lines.field.name'),
                'product_id' => __('im_inventory_lines.field.name'),
                'product_uom' => __('im_inventory_lines.field.name'),
            ]);
        }
        $validator->validate();
    }
}
