<?php

namespace App\Models\HR;

use App\Models\BaseModel;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'hr_employees';
    protected $table = 'hr_employees';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'sinid',
        'passport_id',
        'identification_id',
        'otherid',
        'gender',
        'marital',
        'department_id',
        'parent_id',
        'notes',
        'work_phone',
        'mobile_phone',
        'work_email',
        'work_location',
        'partner_id',
        'address_id',
        'address_home_id',
        'bank_account_id',
        'employee_category_id',
    ];
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
    public function employeeCategory()
    {
        return $this->belongsTo(EmployeeCategory::class, 'employee_category_id', 'id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }
}
