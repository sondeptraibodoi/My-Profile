<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\BA\Customer;
use App\Models\BA\CustomerView;
use App\Traits\ResponseType;
use Illuminate\Http\Request;

class CustomerRequestController extends Controller
{
    use ResponseType;
    public function getCustomersWithAddress(Request $request)
    {
        $customer_code = $request->get('customer_code');
        $query = Customer::with([
            'partner:id,name,parent_id,contact_address,active,address_type,area_id,province_id,district_id,is_customer,is_supplier,is_employee',
            'partner.children' => function ($query) {
                $query->whereHas('resPartnerCategories', fn($q) => $q->where('short_name', 'address'))->with('resPartnerCategories');
            },
        ])->whereHas('partner', function ($q) {
            $q->where('active', true);
        })->whereHas('partner.children.resPartnerCategories', fn($q) => $q->where('short_name', 'address'));
        if (!empty($customer_code)) {
            $query->where('code', 'like', '%' . $customer_code . '%');
        }
        $query = QueryBuilder::for($query, $request)
            ->allowedSearch(['code', 'partner.name']);
        return $this->responseSuccess($query->get());
    }
    public function customersWithAddressList(Request $request)
    {
        $query = Customer::leftjoin('res_partners as partner', 'partner.id', '=', 'ba_customers.partner_id')
            ->leftjoin('res_partners as c_partner', 'partner.id', '=', 'c_partner.parent_id')
            ->leftjoin('res_partner_category_references as cat_ref', 'c_partner.id', '=', 'cat_ref.partner_id')
            ->leftjoin('res_partner_categories as category', 'cat_ref.partner_category_id', '=', 'category.id')
            ->where('category.short_name', '=', 'address')->where('partner.active', true)->select('partner.name', 'partner.id', 'ba_customers.code');
        $query = QueryBuilder::for($query, $request)
            ->allowedSearch(['code', 'partner.name']);

        return $query->get();
    }
    public function getCustomersHasAddress(Request $request)
    {
        $customers = CustomerView::has('partner.children')->get();
        return response()->json(new \App\Http\Resources\Items($customers), 200, []);
    }
}
