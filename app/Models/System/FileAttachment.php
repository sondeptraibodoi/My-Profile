<?php

namespace App\Models\System;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileAttachment extends BaseModel
{
    use HasFactory;
    const LOG_NAME = 'attachment';
    protected $table = 'attachments';
    protected $guard = [
        'id',
        'created_at',
        'updated_at',
    ];
    protected $fillable = [
        'name',
        'datas_fname',
        'description',
        'res_model',
        'res_id',
        'create_date',
        'create_uid',
        'type',
        'url',
        'store_fname',
        'db_datas',
        'file_size',
    ];

}
