<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Logistic\FleetTranOrderStatus;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class FleetTranOrderStatusController extends BaseController
{
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetTranOrderStatus::class);
    }
}
