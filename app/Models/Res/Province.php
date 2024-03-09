<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_province';
    protected $table = 'res_provinces';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'code',
        'short_name',
        'name',
        'priority',
        'level',
        'active',
    ];
    public function districts()
    {
        return $this->hasMany(District::class);
    }
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
