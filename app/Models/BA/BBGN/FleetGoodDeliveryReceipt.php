<?php

namespace App\Models\BA\BBGN;

use App\Models\Auth\User;
use App\Models\BaseModel;
use App\Models\Logistic\FleetGdrStatus;
use App\Models\Logistic\FleetVehicle;
use App\Models\Res\Partner;
use App\Models\Res\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FleetGoodDeliveryReceipt extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'fleet_good_delivery_receipts';
    protected $table = 'fleet_good_delivery_receipts';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'code',
        'fleet_delivery_order_id',
        'partner_id',
        'customer_gdr_code',
        'req_date',
        'address_id',
        'bill_to',
        'gdr_date',
        'vehicle_id',
        'status_id',
        'actual_gdr_date',
        'attention_from_customer',
        'attention_from_internal',
        'sales_person_id',
        'payment_term_id',
        'payment_type_id',
        'production_amount',
        'purchasing_amount',
        'total',
        'description',
        'note',
        'updated_by_id',
        'created_by_id',
        'is_deleted',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class, 'partner_id');
    }

    public function address()
    {
        return $this->belongsTo(Partner::class, 'address_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(FleetVehicle::class, 'vehicle_id');
    }

    public function status()
    {
        return $this->belongsTo(FleetGdrStatus::class, 'status_id');
    }

    public function salesPerson()
    {
        return $this->belongsTo(Partner::class, 'sales_person_id');
    }

    public function paymentTerm()
    {
        return $this->belongsTo(PaymentType::class, 'payment_term_id');
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function gdrLines()
    {
        return $this->hasMany(FleetGdrLineModel::class, 'gdr_id');
    }
}
