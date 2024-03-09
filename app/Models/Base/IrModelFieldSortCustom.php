<?php

namespace App\Models\Base;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IrModelFieldSortCustom extends BaseModel
{
    use HasFactory;
    protected $fillable = [
        'model_field_id',
        'user_id',
        'sort_type',
        'index',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    public function modelField()
    {
        return $this->belongsTo(IrModelField::class, 'model_field_id');
    }
}
