<?php

namespace App\Models\IM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductProductionSystem extends Model
{
    use HasFactory;

    const LOG_NAME = 'im_product_production_system';
    protected $table = 'im_product_production_system';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'im_product_id',
        'product_system_id',
    ];
}
