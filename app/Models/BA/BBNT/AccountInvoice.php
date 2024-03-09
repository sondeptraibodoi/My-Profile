<?php

namespace App\Models\BA\BBNT;

use App\Models\BaseModel;
use App\Models\Res\Company;
use App\Models\Res\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountInvoice extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'account_invoices';
    protected $table = 'account_invoices';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'origin',
        'supplier_invoice_number',
        'type',
        'number',
        'state',
        'date_invoice',
        'sent',
        'partner_id', 
        'sub_total',
        'total',
        'company_id',
        'check_total',
        'partner_bank_id',
        'user_id',
        'comment',
        'reconciled', 
    ];

    public function accountInvoiceLines()
    {
        return $this->hasMany(AccountInvoiceLine::class, 'invoice_id');
    }
    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
