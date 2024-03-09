<?php

namespace App\Models\HR;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'hr_department';
    protected $table = 'hr_departments';
    protected $fillable = [
        'code',
        'name',
        'description',
        'parent_id',
        'manager_id',
        'active',
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id')->orderBy('id', 'asc');
    }
}
