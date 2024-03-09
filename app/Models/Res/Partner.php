<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use App\Models\BA\Customer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_partner';
    protected $table = 'res_partners';
    protected $fillable = [
        'short_name',
        'name',
        'title_id',
        'ref',
        'user_id',
        'vat',
        'website',
        'comment',
        'active',
        'is_customer',
        'is_supplier',
        'is_employee',
        'is_company',
        'job_position',
        'address_type',
        'building_number',
        'building_name',
        'street',
        'street2',
        'street3',
        'town',
        'city',
        'zip_code',
        'area_id',
        'district_id',
        'ward_id',
        'province_id',
        'geometry',
        'email',
        'phone',
        'fax',
        'mobile',
        'birthdate',
        'use_parent_address',
        'has_image',
        'image',
        'image_medium',
        'image_small',
        'contact_address',
        'parent_id',
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function parent()
    {
        return $this->belongsTo(Partner::class, 'parent_id');
    }
}
