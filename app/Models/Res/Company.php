<?php

namespace App\Models\Res;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'res_companies';
    public const NAME_COMPANY_DEFAULT = 'Công ty Thuận Thành';
    protected $table = 'res_companies';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'partner_id',
        'default',
        'parent_id',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
}
