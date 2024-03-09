<?php

namespace App\Models\System;

use App\Models\BaseModel;
use App\Models\Base\IrModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceType extends BaseModel
{
    protected $hidden = ['updated_at', 'created_at'];
    public static $INCLUDE = ['conditions', 'conditions.operator', 'model', 'model.fields'];
    protected $table = 'res_resource_types';
    use HasFactory;
    public function conditions()
    {
        return $this->hasMany(ResourceCondition::class, 'type_id')->defaultOrder();
    }
    public function defaultConditions()
    {
        return $this->hasMany(ResourceCondition::class, 'type_id')->where('is_default', true)->defaultOrder();
    }
    public function model()
    {
        return $this->belongsTo(IrModel::class, 'model_id');
    }
}
