<?php

namespace WuTongWan\Flow\Http\Controllers;

class FlowController extends BaseController
{
    public function index()
    {
        $title = '审核流';

        return view('flow::index', compact('title'));
    }


}

