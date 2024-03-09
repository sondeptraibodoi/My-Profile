<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class DepartmentView extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'hr_department';
    protected $table = 'materialized_departments_view';
    protected $fillable = [
        'code',
        'name',
        'description',
        'parent_id',
        'parent_code',
        'parent_name',
        'manager_id',
        'manager_code',
        'manager_name',
        'active'
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
}
