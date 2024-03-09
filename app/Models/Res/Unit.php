<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_unit';
    protected $table = 'res_units';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'description',
        'priority',
        'rounding',
        'factor',
        'uom_type',
        'active',
    ];
}
