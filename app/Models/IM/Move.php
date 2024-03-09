<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\Res\Partner;
use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picking extends BaseModel
{
    use HasFactory;
    protected $table = 'im_moves';
    public $timestamps = FALSE;
    protected $fillable = [
        'name',
        'created_at',
        'move_date',
        'date_expected',
        'product_id',
        'product_qty',
        'product_uom',
        'location_id',
        'location_dest_id',
        'partner_id',
        'prod_lot_id',
        'note',
        'auto_validate',
        'picking_id',
        'state',
    ];
    protected $guard = [
        'id',
    ];
    protected $casts = [
        'auto_validate'  => 'boolean',
    ];
    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'product_uom');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function locationDest()
    {
        return $this->belongsTo(Location::class, 'location_dest_id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function prodLot()
    {
        return $this->belongsTo(ProductionLot::class, 'prod_lot_id');
    }
    public function picking()
    {
        return $this->belongsTo(Picking::class, 'picking_id');
    }
}
