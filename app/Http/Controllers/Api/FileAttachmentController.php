<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ZipHelper;
use App\Http\Controllers\Controller;
use App\Library\QueryBuilder\QueryBuilder;
use App\Models\System\FileAttachment;
use App\Repositories\BaseRepository;
use App\Traits\ResponseType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Storage;
use Str;
use ZipArchive;

class FileAttachmentController extends Controller
{
    use ResponseType;
    protected $ext = ['xls', 'xlsx', 'csv', 'doc', 'docx', 'pdf'];
    protected $ext_image = ['jpeg', 'jpg', 'png', 'raw', 'svg', 'gif', 'webp', 'psd'];
    protected $file_size = 20 * 1024 * 1024;
    protected $repository;
    protected $storage;
    public function __construct()
    {
        $this->repository = new BaseRepository(FileAttachment::class, [FileAttachment::LOG_NAME]);
        $this->storage = Storage::disk("file");
    }
    public function index(Request $request)
    {
        $res_model = $request->get('res_model', null);
        $res_id = $request->get('res_id', null);
        $query = $this->repository->getModel()::query();
        if (isset($res_model)) {
            $query->where('res_model', $res_model);
        }
        if (isset($res_id)) {
            $query->where('res_id', $res_id);
        }
        $query = QueryBuilder::for($query, $request)
            ->allowedAgGrid()
            ->allowedSorts(['id', 'name', 'create_date', 'res_model', 'res_id', 'description', 'created_at', 'updated_at'])
            ->allowedSearch(['name', 'create_date', 'res_model', 'res_id', 'description'])
            ->defaultSort('id')
            ->allowedPagination()
            ->get();
        return response()->json(new \App\Http\Resources\Items($query), 200, []);
    }

    public function destroy($id)
    {
        $model = $this->repository->getModel();
        $file = $model::findOrFail($id);
        if ($file->url !== null) {
            $this->storage->delete($file->url);
        }
        $file->delete();
        return $this->responseSuccess();
    }
    public function show($id)
    {
        $file_info = $this->repository->find($id);
        if ($this->storage->exists($file_info->url)) {
            $mime_type = $this->storage->mimeType($file_info->url);
            $file = $this->storage->get($file_info->url);
            $response = Response::make($file, 200);
            $response->header('Content-Type', $mime_type);
            return $response;
        } else {
            abort(404, 'Không tìm thấy tệp');
        }
    }

    public function downloadFile($id)
    {
        $file_info = $this->repository->find($id);
        if ($this->storage->exists($file_info->url)) {
            return response()->download($this->storage->path($file_info->url), $file_info->name);
        } else {
            abort(404, 'Không tìm thấy tệp');
        }
    }
    public function downloadAll(Request $request)
    {
        $request->validate([
            'res_model' => 'required|string|max:255|min:1',
        ], [
            'res_model.required' => __('file-attachment.required.res_model'),
        ], [
            'res_model' => __('file-attachment.field.res_model'),
        ]);
        $model = $request->get('res_model', null);
        $res_id = $request->get('res_id', null);
        $zip_name = time() . '.zip';
        if (isset($res_id)) {
            $zip = new ZipArchive();
            $zip_path = $this->storage->path($model) . "/$zip_name";
            $zip->open("$zip_path", ZipArchive::CREATE | ZipArchive::OVERWRITE);
            $files = FileAttachment::where('res_id', $res_id)->get();
            foreach ($files as $file) {
                $file_path = $this->storage->path($file->url);
                $relative_path = substr($file_path, strlen($this->storage->path($model)) + 1);
                $zip->addFile($file_path, $relative_path);
            }
            $zip->close();
            return response()->download($zip_path, $zip_name);
        } else {
            $zip = new ZipHelper();
            $dir_path = $model !== null ? $this->storage->path($model) : $this->storage;
            $zip_path = "$dir_path/$zip_name";
            $zip->addFolder($dir_path);
            $zip->zip("$zip_path");
            return response()->download($zip_path, $zip_name);
        }

    }
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'res_model' => 'required|string|max:255|min:1',
        ], [
            'file.required' => __('file-attachment.required.file'),
            'res_model.required' => __('file-attachment.required.res_model'),
        ], [
            'file' => __('file-attachment.field.file'),
            'res_model' => __('file-attachment.field.res_model'),
        ]);
        $file = $request->file("file");
        $ext = $file->extension();
        $size = $file->getSize();
        if ($size > $this->file_size) {
            abort(400, 'Không thể lưu tệp lớn hơn 20MB');
        }
        if (in_array($ext, $this->ext)) {
            $res_model = $request->get("res_model", "fleet_delivery_orders");
            $res_id = $request->get("res_id");
            $file_name = Str::uuid() . '-' . $file->getClientOriginalName();
            $file_path = $this->storage->putFileAs(isset($res_id) ? "/$res_model/$res_id" : "/$res_model", $file, $file_name);
            $attachment = [
                'name' => $file->getClientOriginalName(),
                'datas_fname' => $file->getClientOriginalName(),
                'description' => $request->get('description'),
                'res_model' => $res_model,
                'res_id' => $res_id,
                'create_date' => Carbon::now(),
                'create_uid' => request()->user()->id,
                'url' => $file_path,
                'store_fname' => $file_name,
                'file_size' => $size,
            ];
            $create_file = $this->repository->create($attachment);
            return $this->responseSuccess($create_file);
        } else {
            abort(400, 'Loại tệp không được hỗ trợ');
        }
    }
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'res_model' => 'required|string|max:255|min:1',
            'res_id' => 'required|string|max:255|min:1',
        ], [
            'file.required' => __('file-attachment.required.file'),
            'res_model.required' => __('file-attachment.required.res_model'),
            'res_id.required' => __('file-attachment.required.res_id'),
        ], [
            'file' => __('file-attachment.field.file'),
            'res_model' => __('file-attachment.field.res_model'),
        ]);
        $file = $request->file("file");
        $ext = $file->extension();
        $size = $file->getSize();
        if ($size > $this->file_size) {
            abort(400, 'Không thể lưu tệp lớn hơn 20MB');
        }
        if (in_array($ext, $this->ext_image)) {
            $res_model = $request->get("res_model");
            $res_id = $request->get("res_id");
            $file_name = Str::uuid() . '-' . $file->getClientOriginalName();
            $file_path = $this->storage->putFileAs(isset($res_id) ? "/$res_model/$res_id" : "/$res_model", $file, $file_name);
            $attachment = [
                'name' => $file->getClientOriginalName(),
                'datas_fname' => $file->getClientOriginalName(),
                'description' => $request->get('description'),
                'res_model' => $res_model,
                'res_id' => $res_id,
                'create_date' => Carbon::now(),
                'create_uid' => request()->user()->id,
                'url' => $file_path,
                'store_fname' => $file_name,
                'file_size' => $size,
            ];
            $create_file = $this->repository->create($attachment);
            return $this->responseSuccess($create_file);
        } else {
            abort(400, 'Loại tệp không được hỗ trợ');
        }
    }

    public function getImage(Request $request)
    {
        $request->validate([
            'res_model' => 'required',
            'res_id' => 'required',
        ], [
            'res_model.required' => __('file-attachment.required.res_model'),
            'res_id.required' => __('file-attachment.required.res_id'),
        ], [
            'res_model' => __('file-attachment.field.res_model'),
            'res_id' => __('file-attachment.field.res_id'),
        ]);
        $res_model = $request->get('res_model', null);
        $res_id = $request->get('res_id', null);
        $query = $this->repository->getModel()::query();
        if (isset($res_model)) {
            $query->where('res_model', $res_model);
        }
        if (isset($res_id)) {
            $query->where('res_id', $res_id);
        }
        $query = QueryBuilder::for($query, $request)
            ->allowedAgGrid()
            ->allowedSorts(['id', 'name', 'create_date', 'res_model', 'res_id', 'description', 'created_at', 'updated_at'])
            ->allowedSearch(['name', 'create_date', 'res_model', 'res_id', 'description'])
            ->defaultSort('id')
            ->allowedPagination()
            ->get();
        return response()->json(new \App\Http\Resources\Items($query), 200, []);
    }
}
