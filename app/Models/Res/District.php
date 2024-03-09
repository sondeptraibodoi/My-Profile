<?php

namespace App\Models\Res;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    const LOG_NAME = 'res_district';
    protected $table = 'res_districts';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'name',
        'priority',
        'province_id',
        'level',
        'active',
    ];
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
