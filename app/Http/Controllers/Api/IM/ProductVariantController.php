<?php

namespace App\Http\Controllers\Api\IM;

use App\Http\Controllers\Api\BaseController;
use App\Models\IM\ProductVariant;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class ProductVariantController extends BaseController
{
    public function __construct()
    {
        $this->repository = new BaseRepository(ProductVariant::class);
    }
}
