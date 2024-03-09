<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceCondition extends Model
{
    protected $table = 'res_resource_conditions';
    use HasFactory;
    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'type_id');
    }
    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }
    function scopeDefaultOrder($query)
    {
        $query->orderBy('priority');
    }
}
