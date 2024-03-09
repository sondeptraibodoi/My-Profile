<?php

namespace App\Http\Controllers\Api\HR;

use App\Http\Controllers\Api\BaseController;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\HR\EmployeeView;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class WorkerController extends BaseController
{
    public function __construct()
    {
        $this->repository = new BaseRepository(EmployeeView::class, [EmployeeView::LOG_NAME]);

    }
    public function listWorkerInGroup(Request $request)
    {
        $user = $request->user();
        $employee = EmployeeView::where('partner_id', $user->partner_id)->first();
        if (!$employee || $employee->employee_category_name != "Tổ trưởng Tổ dịch vụ") {
            abort(403, 'Bạn không có quyền truy cập');
        }
        $query = EmployeeView::query();
        $query->where('department_id', $employee->department_id);
        $query = QueryBuilder::for($query, $request)
            ->allowedAgGrid()
            ->allowedSorts([
                'code',
                'sinid',
                'passport_id',
                'identification_id',
                'otherid',
                'gender',
                'marital',
                'department_id',
                'department',
                'employee_category',
                'parent_id',
                'notes',
                'work_phone',
                'mobile_phone',
                'work_email',
                'work_location',
                'partner_id',
                'address_id',
                'address_home_id',
                'bank_account_id',
                'ref',
                'name',
                'short_name',
                'birthdate',
                'email',
                'mobile',
                'phone',
                'contact_address',
                'active',
            ])
            ->allowedSearch([
                'code',
                'sinid',
                'passport_id',
                'identification_id',
                'otherid',
                'gender',
                'marital',
                'department_id',
                'department',
                'employee_category',
                'parent_id',
                'notes',
                'work_phone',
                'mobile_phone',
                'work_email',
                'work_location',
                'partner_id',
                'address_id',
                'address_home_id',
                'bank_account_id',
                'ref',
                'name',
                'short_name',
                'birthdate',
                'email',
                'mobile',
                'phone',
                'contact_address',
                'active',
            ])
            ->defaultSort('id')
            ->allowedPagination()
            ->get()->reject(function ($record) use ($employee) {
            return $record->id === $employee->id;
        });
        return response()->json(new \App\Http\Resources\Items($query), 200, []);
    }
}
