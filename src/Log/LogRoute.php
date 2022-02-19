<?php

namespace Lilashih\Simphp\Log;

use Lilashih\Simphp\Route\IRegister;
use Lilashih\Simphp\Route\Route;

class LogRoute implements IRegister
{
    public function register(Route $route): Route
    {
        $uri = config('log.viewer.route');

        $route->get($uri, '\\Lilashih\Simphp\\Log\\LogController@index');
        $route->get("{$uri}/{string}", '\\Lilashih\Simphp\\Log\\LogController@show');

        return $route;
    }
}