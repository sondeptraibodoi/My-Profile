<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Picking extends BaseModel
{
    use HasFactory;
    protected $table = 'im_pickings';
    public $timestamps = FALSE;
    protected $fillable = [
        'name',
        'origin',
        'type',
        'note',
        'location_id',
        'location_dest_id',
        'state',
        'created_at',
        'date_done',
        'product_id',
        'auto_picking',
        'partner_id',
    ];
    protected $guard = [
        'id',
    ];
    protected $casts = [
        'auto_picking'  => 'boolean',
    ];
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function locationDest()
    {
        return $this->belongsTo(Location::class, 'location_dest_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
