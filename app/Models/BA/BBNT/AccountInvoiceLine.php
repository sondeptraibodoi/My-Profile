<?php

namespace App\Models\BA\BBNT;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInvoiceLine extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'account_invoice_lines';
    protected $table = 'account_invoice_lines';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'origin',
        'sequence', 
        'invoice_id',
        'unit_id',
        'product_id',
        'price_unit',
        'quantity',
        'subtotal',
        'company_id',
        'payment_status',
        'partner_id', 
    ];

    public function accountInvoice()
    {
        return $this->belongsTo(AccountInvoice::class, 'invoice_id');
    }
    public function accountMoveLine()
    {
        return $this->hasOne(AccountMoveLine::class, 'invoice_line_id');
    }
}
