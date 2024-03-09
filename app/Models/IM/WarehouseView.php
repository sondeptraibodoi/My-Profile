<?php

namespace App\Models\IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class WarehouseView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_warehouses';
    protected $table = 'materialized_warehouses_view';
    protected $guard = [
        'id',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'partner_id',
        'accounting_warehouse_code',
        'active',
        'employee_id',
        'street',
        'town',
        'city',
        'contact_address',
        'employee_code',
        'employee_name'
    ];
}
