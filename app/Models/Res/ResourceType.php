<?php

namespace App\Models\Res;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    use HasFactory;
    const LOG_NAME = 'res_resource_type';
    protected $table = 'res_resource_types';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'description',
        'priority',
        'model_id',
        'active',
    ];
}
