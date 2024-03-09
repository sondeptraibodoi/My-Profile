<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class BaseModel extends Model
{
    public static $INCLUDE = [];
    public static $SORT = [];
    public static $SEARCH = [];
    public static $FILTER = [];
    public static function validate($type, $info = [])
    {
        $validator = Validator::make($info, [], [], []);
        $validator->validate();
    }
}
