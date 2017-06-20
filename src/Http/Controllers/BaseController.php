<?php

namespace WuTongWan\Flow\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use WuTongWan\Flow\Models\AuditAssociatedUserInformation;

class BaseController extends Controller
{

    /**
     * 获取用户信息
     * @param $user_id
     * @return bool|AuditAssociatedUserInformation
     */
    public function getUserInfoByUserId($user_id)
    {
        // 判断用户信息是否在audit_associated_user_informations表中
        $UserInfo = AuditAssociatedUserInformation::where('origin_user_id','=',$user_id)->first();
        if ($UserInfo) {
            return $UserInfo;
        }

        // 用户不存在时，查询用户原始信息
        $UserOriginInfo = $this->getOriginUserInfoByUserId($user_id);
        if(!$UserOriginInfo){
            return false;
        }

        //原始用户信息存在，写入audit_associated_user_informations表中
        $UserInfo = new AuditAssociatedUserInformation;
        $UserInfo->origin_user_id = $UserOriginInfo->id;
        $UserInfo->name = $UserOriginInfo->name;
        $UserInfo->email = $UserOriginInfo->email;
        $UserInfo->save();

        return $UserInfo;
    }

    /**
     * 获取用户原始信息
     * @param $user_id
     * @return bool
     */
    public function getOriginUserInfoByUserId($user_id)
    {
        if (!$user_id or !$configInfo = $this->userConfigInfo()) {
            return false;
        }

        list($connection, $user_table, $user_id_field, $user_name_field, $user_email_field) = array_values($configInfo);

        return DB::connection($connection)
            ->table($user_table)
            ->select("$user_id_field as id", "$user_name_field as name", "$user_email_field as email")
            ->where("$user_id_field", $user_id)
            ->first();
    }

    /**
     * 根据输入的关键值（姓名或邮箱），获取用户原始信息
     * @param string $name
     * @param int    $limit
     * @return bool
     */
    public function getOriginUserInfoByNameOrEmail($name = '', $limit = 10)
    {
        if (!$name or !$configInfo = $this->userConfigInfo()) {
            return false;
        }

        list($connection, $user_table, $user_id_field, $user_name_field, $user_email_field) = array_values($configInfo);

        return DB::connection($connection)
            ->table($user_table)
            ->select("$user_id_field as id", "$user_name_field as name", "$user_email_field as email")
            ->where($user_name_field, 'like', "%$name%")
            ->orWhere($user_email_field, 'like', "%$name%")
            ->limit($limit)
            ->get();
    }

    /**
     * 用户表配置信息
     * @return array|bool
     */
    public function userConfigInfo()
    {
        $connection = Config::get('flow.connection');
        $user_table = Config::get('flow.user_table');
        $user_id_field = Config::get('flow.user_id_field');
        $user_name_field = Config::get('flow.user_name_field');
        $user_email_field = Config::get('flow.user_email_field');

        if (!$connection or !$user_table or !$user_id_field or !$user_name_field or !$user_email_field or !$user_name_field) {
            return false;
        }

        return compact('connection', 'user_table', 'user_id_field', 'user_name_field', 'user_email_field');
    }


}

