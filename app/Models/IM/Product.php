<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\IM\ProductCategory;
use App\Models\Res\ProductionSystem;
use App\Models\Res\Unit;
use App\Models\System\FileAttachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

class Product extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_products';
    protected $table = 'im_products';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
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
    ];

    protected $appends = [
        // 'image',
        'unit_code',
        'unit_name',
    ];

    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_products,code',
                'name' => 'required|string|max:255|min:1',
                'product_category_id' => 'required|numeric',
                'hs_code' => 'max:6',
                'accountant_product_code' => 'max:25',
                'unit_id' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ], [
                'code.unique' => __('product.unique.code'),
                'code.required' => __('product.required.code'),
                'name.required' => __('product.required.name'),
                'product_category_id.required' => __('product.required.product_category_id'),
                'unit_id.required' => __('product.required.unit_id'),
            ], [
                'code' => __('product.field.code'),
                'name' => __('product.field.name'),
                'product_category_id' => __('product.field.product_category_id'),
                'hs_code' => __('product.field.hs_code'),
                'accountant_product_code' => __('product.field.accountant_product_code'),
                'unit_id' => __('product.field.unit_id'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_products,code,' . $id,
                'name' => 'required|string|max:255|min:1',
                'product_category_id' => 'required|numeric',
                'hs_code' => 'max:6',
                'accountant_product_code' => 'max:25',
                'unit_id' => 'required|numeric',
                'active' => 'required|boolean',
                'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ], [
                'code.unique' => __('product.unique.code'),
                'code.required' => __('product.required.code'),
                'name.required' => __('product.required.name'),
                'product_category_id.required' => __('product.required.product_category_id'),
                'unit_id.required' => __('product.required.unit_id'),
                'active.required' => __('product.required.active'),
            ], [
                'code' => __('product.field.code'),
                'name' => __('product.field.name'),
                'product_category_id' => __('product.field.product_category_id'),
                'hs_code' => __('product.field.hs_code'),
                'accountant_product_code' => __('product.field.accountant_product_code'),
                'unit_id' => __('product.field.unit_id'),
                'active' => __('product.field.active'),
            ]);
        }
        $validator->validate();
    }
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
    public function imageAttachment()
    {
        return $this->hasOne(FileAttachment::class, 'res_id');
    }
    // public function getImageAttribute()
    // {
    //     $attachment = FileAttachment::where('res_id', $this->id)->select('id', 'res_id', 'db_datas')->first();
    //     $image = null;
    //     if (!empty($attachment)) {
    //         if (isset($attachment->db_datas) && is_resource($attachment->db_datas) && get_resource_type($attachment->db_datas) === 'stream') {
    //             $my_bytea = stream_get_contents($attachment->db_datas);
    //             $attachment->db_datas = $my_bytea;
    //             $image = $attachment->db_datas;
    //         }
    //     }
    //     return $image;
    // }
    public function getUnitCodeAttribute()
    {
        $name = '';
        $partner = Unit::where('id', $this->unit_id)->first();
        if (!empty($partner)) {
            $name = $partner->code;
        }
        return $name;

    }
    public function getUnitNameAttribute()
    {
        $name = '';
        $partner = Unit::where('id', $this->unit_id)->first();
        if (!empty($partner)) {
            $name = $partner->name;
        }
        return $name;

    }
}
