<?php

namespace App\Models\Res;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    const LOG_NAME = 'res_device';
    protected $table = 'res_devices';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'ip',
        'country_name',
        'country_code',
        'region_name',
        'region_code',
        'latitude',
        'longitude',
        'user_agent',
        'device',
        'browser',
        'platform',
        'device_id',
        'user_id',
        'logout',
        'token_id',
        'last_login',
    ];
}
