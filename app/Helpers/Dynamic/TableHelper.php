<?php

namespace App\Helpers\Dynamic;

use App\Helpers\System\CacheHelper;
use App\Models\Base\IrModel;
use App\Models\Base\IrModelFeature;

final class TableHelper
{
    public static function getTable($table_id, $check = true)
    {
        $table = CacheHelper::getDataCache('table_struct_', $table_id, function () use ($table_id) {
            return IrModel::with(['fields'])->find($table_id);
        });
        if ($check && empty($table)) {
            abort(404);
        }
        return $table;
    }
    public static function getTableByCode($code, $check = true)
    {
        $table = CacheHelper::getDataCache('table_struct_code_', $code, function () use ($code) {
            return IrModel::with(['fields'])->where('code', $code)->first();
        });
        if ($check && empty($table)) {
            abort(404);
        }
        return $table;
    }
    public static function removeCacheTable($table_id)
    {
        $table = CacheHelper::get('table_struct_', $table_id);
        if (isset($table)) {
            CacheHelper::forget('table_struct_', $table->getKey());
            CacheHelper::forget('table_struct_code_', $table->code);
        }
    }
    public static function getQuery(IrModel $table, $table_name = null)
    {
        if (empty($table_name)) {
            $table_name = $table->getTableDbName();
        }
        $query = IrModelFeature::table($table_name, $table)->query();
        return $query;
    }
}
