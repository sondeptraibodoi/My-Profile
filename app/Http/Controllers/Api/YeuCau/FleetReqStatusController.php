<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Models\Logistic\FleetReqStatus;
use App\Repositories\BaseRepository;

class FleetReqStatusController extends BaseController
{
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetReqStatus::class);
    }
}
