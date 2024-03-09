<?php

namespace App\Models\HR;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeCategory extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'hr_employee_category';
    protected $table = 'hr_employee_categories';
    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'active'
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'active' => 'boolean',
    ];
}
