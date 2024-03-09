<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrModeFilter extends Model
{
    use HasFactory;
    protected $casts = [
        'context' => 'object',
        'domain' => 'object',
    ];
    protected $fillable = [
        'name',
        'model_id',
        'context',
        'domain',
        'user_id'
    ];
}
