<?php

namespace App\Http\Controllers\Api\IM;

use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\IM\Product;
use App\Models\IM\ProductProductionSystem;
use App\Models\IM\ProductView;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseType;
    protected $repository;
    public function __construct()
    {
        $this->repository = new BaseRepository(Product::class, [Product::LOG_NAME]);
    }
    public function index(Request $request)
    {
        $query = ProductView::query()->select([
            'code',
            'name',
            'qlct_code',
            'hs_code',
            'id',
            'unit_name',
            'unit_code',
        ]);
        $query = QueryBuilder::for($query, $request)
            ->allowedAgGrid()
            ->allowedSorts([
                'code',
                'name',
                'hs_code',
                'qlct_code',
                'unit_name',
                'unit_code',
                'id',
            ])
            ->allowedSearch([
                'code',
                'name',
                'hs_code',
                'qlct_code',
                'unit_name',
                'unit_code',
                'id',
            ])
            ->defaultSort('id')
            ->allowedPagination()
            ->get();
        return response()->json(new \App\Http\Resources\Items($query), 200, []);
    }

    public function show($id)
    {
        $data = ProductView::find($id);
        return $this->responseSuccess($data);
    }
    public function store(Request $request)
    {
        Gate::authorize('read-model-feature', $this->repository->getModel());
        $data = $request->except(['production_systems']);
        $this->repository->validate('create', $data);
        try {
            DB::beginTransaction();

            $product = $this->repository->create($data);
            $production_systems = $request->get('production_systems');
            $image = $request->file('image');

            if ($production_systems !== null) {
                $production_systems = array_values(array_filter($production_systems, function ($value) {
                    return !is_null($value);
                }));
                foreach ($production_systems as $production_system_id) {
                    ProductProductionSystem::create([
                        "im_product_id" => $product->id,
                        "product_system_id" => $production_system_id,
                    ]);
                }
            }
            $this->handleImageData($image, $product);
            DB::commit();
            return $this->responseSuccess($product);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->responseError();
        }
    }
    public function update(Request $request, $id)
    {
        Gate::authorize('read-model-feature', $this->repository->getModel());
        $data = $request->except(['production_systems']);

        if (!empty($data['active'])) {
            $data['active'] = $booleanValue = filter_var($data['active'], FILTER_VALIDATE_BOOLEAN);
        }
        $this->repository->validate('update', $data, $id);
        $product = $this->repository->update($id, $data);
        $image = $request->file('image');

        $production_systems = $request->get('production_systems');
        if ($production_systems !== null) {
            ProductProductionSystem::where('im_product_id', $id)->delete();
            foreach ($production_systems as $production_system_id) {
                ProductProductionSystem::create([
                    "im_product_id" => $product->id,
                    "product_system_id" => $production_system_id,
                ]);
            }
        }
        $this->handleImageData($image, $product);
        return $this->responseSuccess($product);
    }
    public function destroy($id)
    {
        ProductProductionSystem::where('im_product_id', $id)->delete();
        $data = $this->repository->find($id);
        $data->imageAttachment()->delete();
        $data->delete();
        return $this->responseSuccess();
    }

    public function units()
    {
        $data = DB::table("res_units")->orderBy('priority')->get();
        return $this->responseSuccess($data);
    }

    public function handleImageData($image, $model)
    {
        if (!empty($image)) {
            $modelName = $model->getTable();
            $imageName = $image->getClientOriginalName();
            $imageSize = $image->getSize();
            $imageContent = file_get_contents($image->getRealPath());
            $byteaData = base64_encode($imageContent);
            $attachment = [
                'name' => $imageName,
                'datas_fname' => $imageName,
                'description' => $imageName,
                'res_model' => $modelName,
                'res_id' => $model->id,
                'create_date' => Carbon::now(),
                'create_uid' => request()->user()->id,
                'db_datas' => $byteaData,
                'file_size' => $imageSize,
            ];
            if ($model->imageAttachment()->exists()) {
                $model->imageAttachment()->update($attachment);
            } else {
                $model->imageAttachment()->create($attachment);
            }
        } else {
            $model->imageAttachment()->delete();
        }
    }
}
