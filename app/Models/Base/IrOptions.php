<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrOptions extends Model
{
    use HasFactory;
    protected $fillable = [
        'key',
        'value',
        'autoload',
    ];
    protected $casts = [
        'autoload' => 'boolean',
    ];
}
