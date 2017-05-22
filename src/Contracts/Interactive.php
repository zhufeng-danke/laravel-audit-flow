<?php

namespace WuTongWan\Flow\Contracts;

interface Interactive
{
    /**
     * 查询审核信息
     * @param string $origin_user_id 业务中用户ID，可选
     * @param string $bill_id 业务中单据ID，可选
     * @return bool|mixed
     */
    public function queryCheck($origin_user_id = '', $bill_id = '');

    /**
     * 存储审核结果
     * @param        $audit_user_id
     * @param        $bill_id
     * @param        $current_user_id
     * @param string $action
     * @param string $comment
     * @return bool
     */
    public function storeCheckResult($audit_user_id, $bill_id, $current_user_id, $action = '', $comment = '');

}
