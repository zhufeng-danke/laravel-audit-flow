# 扩展包开发环境配置

## 引入扩展包仓库

    git remote add -f laravel-audit-flow https://github.com/zhufeng-danke/laravel-audit-flow.git
    
## 添加扩展包目录及其代码

    git subtree add -P laravel-audit-flow laravel-audit-flow master
    
## 配置项目composer.json文件
在autoload的psr-4中添加扩展包的加载路径，添加后如下：

        "autoload": {
            "classmap": [
                "database"
            ],
            "psr-4": {
                "App\\": "app/",
                "WuTongWan\\Flow\\": "laravel-audit-flow/src"
            }
        },
    
## 添加扩展包的ServiceProvider到项目config/app.php的providers中，添加后如下：
    
        /*
         * Package Service Providers...
         */
        Laravel\Tinker\TinkerServiceProvider::class,
        WuTongWan\Flow\FlowServiceProvider::class,
    
## 重新生成自动加载文件
执行如下命令：
    
    composer dump-autoload
    
## 发布扩展包文件    
执行如下命令：
    
    php artisan vendor:publish
    
命令执行后:

    在config目录下新增flow.php文件，即扩展包的配置文件（配置用户信息表相关信息）;
    
    在routes目录下新增flow.php文件，即扩展包的路由文件；在web.php文件末尾中添加 "require base_path('routes/flow.php');" 语句引入扩展包路由；以此方便在路由文件flow.php中添加权限控制。

    
## 配置.env文件中数据库相关字段

## 迁移数据表
执行如下命令：

    php artisan migrate
    
执行后扩展包相关的表写入配置的数据库
    

至此，可进行扩展包的二次开发了 ～ ～。
