<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use Gate;
use Illuminate\Http\Request;

class BaseController extends Controller
{

    protected $disabled_check = true;
    protected $repository;
    protected $extra_create;
    protected $extra_after_create;
    protected $extra_update;
    protected $extra_delete;
    protected $extra_list;
    public function index(Request $request)
    {
        $query = $this->repository->getModel()::query();
        if (!$this->disabled_check) {
            Gate::authorize('read-model-feature', $this->repository->getModel());
        }

        $query = QueryBuilder::for($query, $request)
            ->initFromModelForList()
            ->allowedPagination();
        if (!empty($this->extra_list)) {
            $extra_list = $this->extra_list;
            $extra_list($query, $request);
        }
        return response()->json(new \App\Http\Resources\Items($query->get()), 200, []);
    }
    public function show(Request $request, $id)
    {
        $query = $this->repository->getModel()::query();
        if (!$this->disabled_check) {
            Gate::authorize('read-model-feature', $this->repository->getModel());
        }

        $query = QueryBuilder::for($query, $request)
            ->initFromModelForShow();
        $data = $query->findOrFail($id);
        return $this->responseSuccess($data);
    }
    public function store(Request $request)
    {
        $info = $request->all();
        if (!$this->disabled_check) {
            Gate::authorize('create-model-feature', $this->repository->getModel());
        }

        $this->repository->validate('create', $info);
        $data = $this->repository->create($info, $this->extra_create, $this->extra_after_create);
        return $this->responseSuccess($data);
    }
    public function update(Request $request, $id)
    {
        $info = $request->all();
        if (!$this->disabled_check) {
            Gate::authorize('update-model-feature', $this->repository->getModel());
        }

        $this->repository->validate('update', $info, $id);
        $data = $this->repository->update($id, $info, $this->extra_update);
        return $this->responseSuccess($data);
    }
    public function destroy($id)
    {
        if (!$this->disabled_check) {
            Gate::authorize('delete-model-feature', $this->repository->getModel());
        }

        $result = $this->repository->delete($id, $this->extra_delete);
        return $this->responseSuccess($result);
    }
}
