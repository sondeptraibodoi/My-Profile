<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\UserStatus;
use App\Models\HR\EmployeeView;
use App\Models\Res\Device;
use App\Models\Res\Partner;
use App\Models\System\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\HasApiTokens;
use Validator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'res_users';
    const LOG_NAME = 'user';
    public static $INCLUDE = ['groups', 'employee'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'email',
        'user',
        'status',
        'partner_id',
        'menu_id',
        'signature',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'res_user_groups');
    }
    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }
    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'login' => ['required', 'string', 'max:255', 'min:1', Rule::unique('res_users', 'login')],
                'email' => ['nullable', 'string', 'max:255', 'email', Rule::unique('res_users', 'email')],
                'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            ], [], [
                'email' => __('user-manager-modal.field.email'),
                'login' => __('user-manager-modal.field.login'),
                'password' => __('user-manager-modal.field.password'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'login' => ['required', 'string', 'max:255', 'min:1', Rule::unique('res_users', 'login')->ignore($id, 'id')],
                'email' => ['nullable', 'string', 'max:255', 'email', Rule::unique('res_users', 'email')->ignore($id, 'id')],
            ], [], [
                'email' => __('user-manager-modal.field.email'),
                'login' => __('user-manager-modal.field.login'),
            ]);
        }
        $validator->validate();
    }
    public function employee()
    {
        return $this->hasOne(EmployeeView::class, 'partner_id', 'partner_id');
    }
    public function partner()
    {
        return $this->hasOne(Partner::class, 'id', 'partner_id');
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'user_id', 'id')->where('logout', false)->orderBy('updated_at', 'desc');
    }
}
