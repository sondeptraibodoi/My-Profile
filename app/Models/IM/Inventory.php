<?php

namespace App\Models\IM;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends BaseModel
{
    use HasFactory;
    protected $table = 'im_inventories';
    public $timestamps = FALSE;
    protected $fillable = [
        'name',
        'created_at',
        'date_done',
        'state',
        'warehouse_id',
    ];
    protected $guard = [
        'id',
    ];
    protected $casts = [
        'created_at'  => 'date',
    ];
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function inventoryLine()
    {
        return $this->belongsTo(InventoryLine::class, 'inventory_id');
    }
}
