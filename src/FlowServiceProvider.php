<?php

namespace WuTongWan\Flow;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use WuTongWan\Flow\Containers\Interactive;

class FlowServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        //配置文件
        $this->publishes([
            __DIR__ . '/config/flow.php' => config_path('flow.php'),
            __DIR__.'/routes.php' => base_path('routes/flow.php'),
        ]);

        //路由
//        $this->loadRoutesFrom(__DIR__.'/routes.php');

        //模版文件
        $this->loadViewsFrom(realpath(__DIR__ . '/views'), 'flow');

        //数据库迁移文件
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function register()
    {
        $this->app->bind('flow', function (){
           return new Interactive();
        });
    }
}
