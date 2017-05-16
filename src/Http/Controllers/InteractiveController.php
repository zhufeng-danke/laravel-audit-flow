<?php

namespace WuTongWan\Flow\Http\Controllers;

use WuTongWan\Flow\Facades\Flow;

class InteractiveController extends BaseController
{

    public function index()
    {
//        dd($this->getOriginUserInfoByNameOrEmail('dan'));
        dd($this->getUserInfoByUserId(1));

        echo Flow::createFlow() . '<br/>';
        echo Flow::queryCheck() . '<br/>';
        echo Flow::checkResult() . '<br/>';
        echo Flow::flowInfo() . '<br/>';
    }

}

