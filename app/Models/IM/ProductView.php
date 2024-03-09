<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\IM\ProductCategory;
use App\Models\Res\ProductionSystem;
use App\Models\Res\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_products';
    protected $table = 'materialized_products_view';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    // protected $appends = ['production_systems'];
    protected $fillable = [
        'code',
        'name',
        'product_category_id',
        'hs_code',
        'accountant_product_code',
        'qlct_code',
        'unit_id',
        'conversion_factor',
        'is_consumable',
        'active',
        'product_catetories_code',
        'product_catetories_name',
        'unit_name',
        'unit_code',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function productionSystem()
    {
        return $this->belongsToMany(ProductionSystem::class, 'im_product_production_system', 'im_product_id', 'product_system_id');
    }
    public function getProductionSystemsAttribute()
    {
        $production_systems = ProductProductionSystem::where('im_product_id', $this->attributes['id'])->get()->pluck('product_system_id');
        return $production_systems;
    }
}
