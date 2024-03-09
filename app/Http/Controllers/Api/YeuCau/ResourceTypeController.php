<?php

namespace App\Http\Controllers\Api\YeuCau;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\System\ResourceType;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class ResourceTypeController extends BaseController
{
    protected $disabled_check = true;
    public function __construct()
    {
        $this->repository = new BaseRepository(ResourceType::class);
        $this->extra_list = function ($query, $request) {
            $query->defaultOrder();
            $query->active();
        };
    }
}
