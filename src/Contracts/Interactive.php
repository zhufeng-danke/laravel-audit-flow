<?php

namespace WuTongWan\Flow\Contracts;

interface Interactive
{
    // 创建审核流
    public function createFlow($bill_id, $user_id, $name);

    // 查询审核人信息
    public function queryCheck();

    // 返回审核结果
    public function checkResult();

    // 返回流信息
    public function flowInfo();
}
