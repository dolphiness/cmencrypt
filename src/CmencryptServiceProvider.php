<?php

namespace Stardust\crypt;

use Illuminate\Support\ServiceProvider;

class CmencryptServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('cmencrypt', function () {
            return new Cmencrypt();
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/key.php' => base_path('config/key.php'), // 发布配置文件到 lumen 的config 下
        ]);
    }
}
