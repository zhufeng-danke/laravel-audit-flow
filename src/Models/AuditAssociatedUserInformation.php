<?php

namespace WuTongWan\Flow\Models;

class AuditAssociatedUserInformation extends BaseModel
{
    //获取用户列表
    public function getList()
    {
        $list = self::select("*")->get();
        return $list;
    }
}
