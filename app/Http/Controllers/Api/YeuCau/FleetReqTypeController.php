<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class FleetReqTypeController extends BaseController
{
    public function __construct()
    {
        $this->repository = new BaseRepository(FleetReqType::class);
    }
}
