<?php

namespace Jwt\Provider;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Jwt\Service\JwtService;

class JwtProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/jwt_service_config.php', 'jwtServiceConfig'
            );
        
        $this->app->singleton("jwtservice", function ($app){
            $key = $app["config"]["jwtServiceConfig"]["jwtServiceKey"];
            return JwtService::createWithKey($key);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // ...
    }
}
