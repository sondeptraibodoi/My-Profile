<?php

namespace App\Models\System;

use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\Base\IrModelAccess;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends BaseModel
{
    use HasFactory;

    const LOG_NAME = 'group';
    public static $INCLUDE = ['users', 'access', 'groupApplications'];
    protected $fillable = [
        'name',
        'description',
    ];

    protected $appends = ['applications'];
    protected $table = 'res_groups';
    public function users()
    {
        return $this->belongsToMany(User::class, 'res_user_groups');
    }
    public function access()
    {
        return $this->hasOne(IrModelAccess::class, 'group_id');
    }
    public function groupApplications()
    {
        return $this->belongsToMany(Application::class, 'res_application_group');
    }
    public function getApplicationsAttribute()
    {
        if ($this->attributes) {
            $applications = ApplicationGroupUser::where('group_id', $this->attributes['id'])->get()->pluck('application_id');
            return $applications;
        } else {
            return;
        }
    }
}
