<?php

namespace App\Http\Controllers\Api\Translation;

use App\Http\Controllers\Controller;
use App\Models\Base\IrAction;
use Illuminate\Support\Facades\File;

class TranslationController extends Controller
{
    public function index($language, $file)
    {
        $action = IrAction::where('type', $file)->get()->mapWithKeys(function ($item, $key) {
            return [$item['code'] => $item['name']];
        })->toArray();
        $path = resource_path("lang/{$language}/{$file}.php");
        $messages = [];
        if (File::exists($path)) {
            $messages = include($path);
        }
        $data = array_merge($messages, $action);
        return response()->json($data);
    }
}
