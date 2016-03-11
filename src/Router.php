<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 11.03.16
 * Time: 11:58
 */

namespace Nebo15\Changelog;

use Laravel\Lumen\Application;

class Router
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function api($api_prefix = '', array $middleware = [], $controller_name = null)
    {
        $controller_name = $controller_name ?: 'Nebo15\Changelog\Controller';

        $this->app->get("$api_prefix/changelog/{table}/{model_id}", [
            "uses" => "$controller_name@all",
            'middleware' => $middleware
        ]);

        $this->app->get("/$api_prefix/changelog/{table}/{model_id}/diff", [
            "uses" => "$controller_name@diff",
            'middleware' => $middleware
        ]);

        $this->app->get("/$api_prefix/changelog/{table}/{model_id}/rollback/{changelog_id}", [
            "uses" => "$controller_name@rollback",
            'middleware' => $middleware
        ]);
    }
}
