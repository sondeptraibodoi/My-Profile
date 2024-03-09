<?php

namespace App\Models\IM;

use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveConsume extends Model
{
    use HasFactory;
    protected $table = 'im_move_consumes';
    protected $fillable = [
        'move_date',
        'product_id',
        'product_qty',
        'product_uom',
        'location_id',
        'note',
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
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
}
