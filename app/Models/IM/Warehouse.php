<?php

namespace App\Models\IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\HR\Employee;
use Validator;

class Warehouse extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'im_warehouses';
    protected $table = 'im_warehouses';
    protected $guard = [
        'id',
        'created_at',
        'updated_at'
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'location_id',
        'accounting_warehouse_code',
        'active',
        'employee_id'
    ];

    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_warehouses,code',
                'name' => 'required|string|max:255|min:1',
                'accounting_warehouse_code' => 'max:10',
                'street' => 'max:255',
            ], [
                'code.required' => __('warehouse.required.code'),
                'code.unique' => __('warehouse.unique.code'),
                'name.required' => __('warehouse.required.name'),
            ], [
                'code' => __('warehouse.field.code'),
                'name' => __('warehouse.field.name'),
                'accounting_warehouse_code' => __('warehouse.field.accounting_warehouse_code'),
                'street' => __('warehouse.field.street'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'code' => 'required|string|max:10|min:1|unique:im_warehouses,code,' . $id,
                'name' => 'required|string|max:255|min:1',
                'accounting_warehouse_code' => 'max:10',
                'active' => 'required|boolean',
                'street' => 'max:255',
            ], [
                'code.required' => __('warehouse.required.code'),
                'code.unique' => __('warehouse.unique.code'),
                'name.required' => __('warehouse.required.name'),
                'active.required' => __('warehouse.required.active'),
            ], [
                'code' => __('warehouse.field.code'),
                'name' => __('warehouse.field.name'),
                'accounting_warehouse_code' => __('warehouse.field.accounting_warehouse_code'),
                'active' => __('warehouse.field.active'),
                'street' => __('warehouse.field.street'),
            ]);
        }
        $validator->validate();
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'warehouse_id');
    }
}
