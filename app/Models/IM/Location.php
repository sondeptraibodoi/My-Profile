<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Validator;

class Location extends BaseModel
{
    use HasFactory;
    protected $table = 'im_locations';
    public static $INCLUDE = ['location', 'partner'];
    public static $SORT = ['id', 'name', 'active', 'usage', 'location_id', 'partner_id', 'scrap_location', 'comment'];
    public static $SEARCH = ['name', 'usage', 'comment'];
    protected $fillable = [
        'name',
        'active',
        'usage',
        'location_id',
        'partner_id',
        'scrap_location',
        'comment',
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $casts = [
        'active'  => 'boolean',
        'scrap_location'  => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'location_id');
    }
    public static function validate($type, $info = [], $id = null)
    {
        if ($type == 'create') {
            $validator = Validator::make($info, [
                'name' => 'required|string|max:255|min:1',
            ], [
                'name.required' => __('im_locations.required.name'),
            ], [
                'name' => __('im_locations.field.name'),
            ]);
        } else if ($type == 'update') {
            $validator = Validator::make($info, [
                'name' => 'required|string|max:255|min:1',
                'active' => 'required|boolean',
                'usage' => 'required|string',
            ], [
                'name.required' => __('im_locations.required.name'),
                'active.required' => __('im_locations.required.active'),
                'usage.required' => __('im_locations.required.usage'),
            ], [
                'name' => __('im_locations.field.name'),
                'active' => __('im_locations.field.active'),
                'usage' => __('im_locations.field.usage'),
            ]);
        }
        $validator->validate();
    }
}
