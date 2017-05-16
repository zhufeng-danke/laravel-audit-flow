<?php

namespace WuTongWan\Flow\Containers;

class Interactive implements \WuTongWan\Flow\Contracts\Interactive
{

    /**
     * 创建审核流
     * 输入：bill_id, user_id, name
     * 输出：重定向到审核配置页面
     */
    public function createFlow($bill_id, $user_id, $name){
        //判断bill_id

        return redirect()->route('flow-index');
    }

    // 查询审核人信息
    public function queryCheck(){
        return 'queryCheck';
    }

    // 返回审核结果
    public function checkResult(){
        return 'checkResult';
    }

    // 返回流信息
    public function flowInfo(){
        return 'flowInfo';
    }

}
