<?php

namespace App\Models\Base;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrModelField extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'type',
        'required',
        'read_only',
        'is_unique',
        'model_id',
        'selectable',
        'selection',
        'relation',
        'relation_field',
        'domain',
        'default_value',
        'placeholder',
        'mask',
        'default_show_on_table',
        'search_by_fulltext_search',
        'state',
        'on_delete',
        'index',
        'description'
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'required' => 'boolean',
        'is_unique' => 'boolean',
        'read_only' => 'boolean',
        'selectable' => 'boolean',
        'selection' => 'array',
    ];
    public function model()
    {
        return $this->belongsTo(IrModel::class, 'model_id');
    }
    public function canHandle($field): bool
    {
        return $this->code === $field;
    }
    public function customUser()
    {
        return $this->hasMany(IrModelFieldCustom::class, 'model_id');
    }
    public function scopeByUser($query)
    {
        $user = request()->user();
        if (isset($user)) {
            $query
                ->leftJoin('ir_model_field_customs as b', function ($join) use ($user) {
                    $join->on('b.model_field_id', '=', 'ir_model_fields.id')
                        ->where('b.user_id', '=', $user->getKey());
                })
                ->select(
                    'ir_model_fields.id',
                    'ir_model_fields.code',
                    'ir_model_fields.name',
                    'ir_model_fields.type',
                    'ir_model_fields.required',
                    'ir_model_fields.read_only',
                    'ir_model_fields.is_unique',
                    'ir_model_fields.relation',
                    'ir_model_fields.relation_field',
                    'ir_model_fields.selectable',
                    'ir_model_fields.selection',
                    'ir_model_fields.domain',
                    'ir_model_fields.default_value',
                    'ir_model_fields.placeholder',
                    'ir_model_fields.mask',
                    'ir_model_fields.model_id',
                    DB::raw('COALESCE(b.default_show_on_table, ir_model_fields.default_show_on_table) as default_show_on_table'),
                    'ir_model_fields.search_by_fulltext_search',
                    'ir_model_fields.state',
                    'ir_model_fields.on_delete',
                    'ir_model_fields.description',
                    DB::raw('COALESCE(b.index, ir_model_fields.index) as index'),
                    'ir_model_fields.created_at',
                    'ir_model_fields.updated_at'
                );
        }
        $query->orderBy('index', 'asc')->orderBy('id');
    }
}
