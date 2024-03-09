<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnitCategory extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_unit_category';
    protected $table = 'res_unit_categories';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
    ];
}
