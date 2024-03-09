<?php

namespace App\Models\IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Validator;

class ProductCategory extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_product_catetories';
    protected $table = 'im_product_catetories';
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
        'is_selected_in_contract'
    ];

    public function product()
    {
        return $this->hasMany(Product::class, 'product_category_id', 'id');
    }
    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_product_catetories,code',
                'short_name' => 'nullable|string|max:50|unique:im_product_catetories,short_name',
                'name' => 'required|string|max:255|min:1',
            ], [
                'code.unique' => __('product-category.unique.code'),
                'code.required' => __('product-category.required.code'),
                'short_name.unique' => __('product-category.unique.short_name'),
                'name.required' => __('product-category.required.name'),
            ], [
                'code' => __('product-category.field.code'),
                'short_name' => __('product-category.field.short_name'),
                'name' => __('product-category.field.name'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_product_catetories,code,' . $id,
                'short_name' => 'nullable|string|max:50|unique:im_product_catetories,short_name,' . $id,
                'name' => 'required|string|max:255|min:1',
                'is_selected_in_contract' => 'required|boolean',
                'active' => 'required|boolean',
            ], [
                'code.unique' => __('product-category.unique.code'),
                'code.required' => __('product-category.required.code'),
                'short_name.unique' => __('product-category.unique.short_name'),
                'name.required' => __('product-category.required.name'),
                'is_selected_in_contract.required' => __('product-category.required.is_selected_in_contract'),
                'active.required' => __('product-category.required.active'),

            ], [
                'code' => __('product-category.field.code'),
                'name' => __('product-category.field.name'),
                'short_name' => __('product-category.field.short_name'),
                'is_selected_in_contract' => __('product-category.field.is_selected_in_contract'),
                'active' => __('product-category.field.active'),
            ]);
        }
        $validator->validate();
    }
    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id')->orderBy('code', 'asc');
    }
}
