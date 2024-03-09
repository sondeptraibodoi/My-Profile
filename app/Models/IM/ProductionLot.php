<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionLot extends BaseModel
{
    use HasFactory;
    protected $table = 'im_production_lots';
    public $timestamps = FALSE;
    protected $fillable = [
        'ref',
        'product_id',
        'created_at',
        'stock_available',
    ];
    protected $guard = [
        'id',
    ];
    protected $casts = [
        'created_at'  => 'date',
    ];
    public function production()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
}
