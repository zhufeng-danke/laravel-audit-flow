<?php

namespace WuTongWan\Flow\Facades;

use Illuminate\Support\Facades\Facade;

class Flow extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'flow';
    }
}

