<?php

namespace App\Models\Base;

use App\Models\System\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrModelAccess extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'model_id',
        'group_id',
        'perm_read',
        'perm_write',
        'perm_create',
        'perm_unlink',
    ];
    protected $casts = [
        'perm_read' => 'boolean', //list
        'perm_write' => 'boolean', //update
        'perm_create' => 'boolean', //create
        'perm_unlink' => 'boolean', //delete
    ];
    public function model()
    {
        return $this->belongsTo(IrModel::class, 'model_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
