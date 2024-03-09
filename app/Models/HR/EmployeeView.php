<?php

namespace App\Models\HR;

use App\Constants\UserStatus;
use App\Models\Auth\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'hr_employee_view';
    protected $table = 'materialized_employees_view';
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
        'department',
        'employee_category',
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
        'ref',
        'name',
        'short_name',
        'birthdate',
        'email',
        'mobile',
        'phone',
        'contact_address',
        'active',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'partner_id', 'partner_id');
    }

    public function userActive()
    {
        return $this->users()->where('status', UserStatus::ACTIVE);
    }
}
