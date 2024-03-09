<?php

namespace App\Models\IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Validator;

class ProductCategoryView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_product_catetories';
    protected $table = 'materialized_product_catetories_view';
    protected $guard = [
        'id',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'code',
        'short_name',
        'name',
        'description',
        'active',
        'parent_id',
        'type',
        'parent_left',
        'parent_right',
        'priority',
        'is_selected_in_contract',
        'parent_name'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'id');
    }
}
