<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrAction extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'help',
        'type',
    ];
}
