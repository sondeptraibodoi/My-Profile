<?php

namespace App\Models\Base;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class IrModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
    ];
    public static $INCLUDE = ['fields', 'accesses', 'filters', 'filter'];
    public static $SEARCH = ['code', 'name'];
    public static $FILTER = [
        'id',
        'code',
        'name',
        'type',
        'description',
    ];
    public static $SORT = [
        'id',
        'code',
        'name',
        'type',
        'description',
    ];
    public function fields()
    {
        $relation =  $this->hasMany(IrModelField::class, 'model_id', 'id');
        return $relation->byUser();
    }
    public function accesses()
    {
        $relation = $this->hasMany(IrModelAccess::class, 'model_id');
        $user = request()->user();
        if (isset($user)) {
            $relation->whereIn('group_id', $user->groups()->select('res_groups.id')->pluck('id'));
        }
        return $relation;
    }
    public function getAccess()
    {
        $accesses = $this->accesses()->get();
        return $accesses->reduce(function ($acc, $cur) {
            if (!$acc) {
                return $cur;
            }
            if ($cur['perm_create']) {
                $acc['perm_create'] = true;
            }
            if ($cur['perm_read']) {
                $acc['perm_read'] = true;
            }
            if ($cur['perm_write']) {
                $acc['perm_write'] = true;
            }
            if ($cur['perm_unlink']) {
                $acc['perm_unlink'] = true;
            }
            return $acc;
        }, [
            'perm_create' => true,
            'perm_read' => true,
            'perm_write' => true,
            'perm_unlink' => true,
        ]);
    }
    public function filters()
    {
        $relation = $this->hasMany(IrModeFilter::class, 'model_id');
        $user = request()->user();
        if (isset($user)) {
            $relation->where('user_id', $user->getKey());
        }
        return $relation->orderBy('user_id', 'desc')->orderBy('created_at', 'desc');
    }
    public function filter()
    {
        $relation = $this->hasOne(IrModeFilter::class,  'model_id', 'id');
        $user = request()->user();
        if (isset($user)) {
            $relation->where(function ($query) use ($user) {
                $query->where('user_id', $user->getKey());
                $query->orWhereNull('user_id');
            });
        }
        return $relation->orderBy('user_id', 'desc')->orderBy('created_at', 'desc');
    }
    public function getTableDbName()
    {
        return $this->code;
    }
    public function scopeIsTable(Builder $query): void
    {
        $query->where('type', 'T-Table');
    }
    public function scopeIsVIew(Builder $query): void
    {
        $query->where('type', 'V-View');
    }
    public function scopeIsForm(Builder $query): void
    {
        $query->where('type', 'F-Form');
    }
}
