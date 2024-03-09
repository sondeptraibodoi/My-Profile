<?php

namespace App\Models\BA\BBNT;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMove extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'account_moves';
    protected $table = 'account_moves';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'ref',
        'state',
        'to_check',
        'partner_id',
        'amount', 
        'date', 
        'narration', 
        'company_id',
        'balance', 
        'created_by_id',
        'updated_by_id',
    ];

    public function accountMoveLines()
    {
        return $this->hasMany(AccountMoveLine::class, 'move_id');
    }
}
