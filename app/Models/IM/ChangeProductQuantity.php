<?php

namespace App\Models\IM;

use App\Models\Auth\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeProductQuantity extends BaseModel
{
    use HasFactory;
    protected $table = 'im_change_product_quantities';
    protected $fillable = [
        'product_id',
        'new_quantity',
        'location_id',
        'created_by_id',
    ];
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
