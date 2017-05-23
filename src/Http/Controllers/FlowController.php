<?php

namespace WuTongWan\Flow\Http\Controllers;

class FlowController extends BaseController
{
    public function index()
    {
        $title = '审核流';

        $model_AuditFlow = new \WuTongWan\Flow\Models\AuditFlow();
        $list = $model_AuditFlow->select("*")->get();
        
        return view('flow::index', compact('title'));
    }


}

