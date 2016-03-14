<?php

/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 14.03.16
 * Time: 14:19
 */

namespace Nebo15\Changelog;

use Illuminate\Support\ServiceProvider as LumenServiceProvider;

class ServiceProvider extends LumenServiceProvider
{
    public function register()
    {
        $this->app->singleton('Nebo15\Changelog\Router', function ($app) {
            return new Router($app);
        });
    }
}
