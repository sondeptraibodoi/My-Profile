<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrModelFieldCustom extends Model
{
    use HasFactory;
    protected $fillable = [
        'model_field_id',
        'user_id',
        'default_show_on_table',
        'index',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'default_show_on_table' => 'boolean',
    ];
    public function modelField()
    {
        return $this->belongsTo(IrModelField::class, 'model_field_id');
    }
}
