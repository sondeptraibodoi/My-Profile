<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Territory extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_territory';
    protected $table = 'res_territories';
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
