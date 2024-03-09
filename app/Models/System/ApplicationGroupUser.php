<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationGroupUser extends Model
{
    use HasFactory;
    const LOG_NAME = 'res_application_group';
    protected $table = 'res_application_group';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'application_id',
        'group_id',
    ];
}
