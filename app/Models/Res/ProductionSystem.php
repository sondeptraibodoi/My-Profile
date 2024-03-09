<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionSystem extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'production_system';
    protected $table = 'production_systems';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'name',
        'description',
        'active',
    ];
}
