<?php

namespace App\Models\BA\BBNT;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMoveLine extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'account_move_lines';
    protected $table = 'account_move_lines';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'invoice_line_id',
        'quantity',
        'unit_id',
        'product_id',
        'debit',
        'credit', 
        'move_id', 
        'narration', 
        'blocked',
        'partner_id',
        'company_id',
        'date', 
        'date_maturity', 
        'balance', 
        'state',
        'amount'
    ];

    public function accountMove()
    {
        return $this->belongsTo(AccountMove::class, 'move_id');
    }
    public function accountInvoiceLine()
    {
        return $this->belongsTo(AccountInvoiceLine::class, 'invoice_line_id');
    }
}
