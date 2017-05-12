<?php

namespace WuTongWan\Flow\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{

    protected function userInfo($name = '', $limit = 10)
    {
        $connection = Config::get('flow.connection');
        $user_table = Config::get('flow.user_table');
        $user_id_field = Config::get('flow.user_id_field');
        $user_name_field = Config::get('flow.user_name_field');
        $user_email_field = Config::get('flow.user_email_field');

        if (!$name | !$connection | !$user_table | !$user_id_field | !$user_name_field | !$user_email_field | !$user_name_field) {
            return false;
        }

        return DB::connection($connection)
            ->table($user_table)
            ->select("$user_id_field as id", "$user_name_field as name", "$user_email_field as email")
            ->where($user_name_field, 'like', "%$name%")
            ->orWhere($user_email_field, 'like', "%$name%")
            ->limit(10)
            ->get();
    }

}

