<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

class ProductVariant extends BaseModel
{
    use HasFactory;
    protected $table = 'im_product_variants';
    protected $fillable = [
        'product_tmpl_id',
        'is_only_child',
        'seller_id',
        'code',
        'external_code',
        'qty_available',
        'virtual_available',
        'active',
    ];
    protected $guard = [
        'id',
        'qty_available',
        'virtual_available',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_only_child'  => 'boolean',
        'active'  => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_tmpl_id');
    }
    public function seller()
    {
        return $this->belongsTo(Partner::class, 'seller_id');
    }

    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'product_tmpl_id' => 'required|numeric',
                'code' => 'max:50',
                'external_code' => 'max:50',
            ], [
                'product_tmpl_id.required' => __('product_variant.required.product_tmpl_id'),
            ], [
                'product_tmpl_id' => __('product_variant.field.product_tmpl_id'),
                'code' => __('product_variant.field.code'),
                'external_code' => __('product_variant.field.external_code'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'product_tmpl_id' => 'required|numeric',
                'active' => 'required|boolean',
                'code' => 'max:50',
                'external_code' => 'max:50',
            ], [
                'product_tmpl_id.required' => __('product_variant.required.product_tmpl_id'),
                'active.required' => __('product_variant.required.active'),
            ], [
                'product_tmpl_id' => __('product_variant.field.product_tmpl_id'),
                'active' => __('product_variant.field.active'),
                'code' => __('product_variant.field.code'),
                'external_code' => __('product_variant.field.external_code'),
            ]);
        }
        $validator->validate();
    }
}
