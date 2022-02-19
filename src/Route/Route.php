<?php

namespace Lilashih\Simphp\Route;

use Lilashih\Simphp\Log\LogRoute;

class Route
{
    protected $uri = [];
    protected $prefix = '';

    public function register()
    {
        $route = $this->setPrefix('');
        $route = (new LogRoute())->register($this);

        return $route;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = ($prefix) ? (trim($prefix, '/') . '/') : '';

        return $this;
    }

    public function match($url): array
    {
        foreach ($this->uri as $uri => $action) {
            preg_match($this->convertPattern($uri), $url, $match);

            if ($match) {
                array_shift($match);
                $parameters = array_map('urldecode', $match);
                return [$action, $parameters];
            }
        }

        return [];
    }

    protected function convertPattern(string $string)
    {
        $string = str_replace("/", "\/", $string);
        $string = str_replace("{number}", "(\d+)", $string);
        $string = str_replace("{string}", "([a-zA-Z0-9(\s)%_.-]+)", $string);

        return '/^'.$string.'$/';
    }

    public function get($route, $action)
    {
        $this->uri = array_merge($this->uri, [
            "GET:{$this->prefix}{$route}" => "{$action}",
        ]);

        return $this;
    }

    public function post($route, $action)
    {
        $this->uri = array_merge($this->uri, [
            "POST:{$this->prefix}{$route}" => "{$action}",
        ]);

        return $this;
    }

    public function put($route, $action)
    {
        $this->uri = array_merge($this->uri, [
            "PUT:{$this->prefix}{$route}" => "{$action}",
        ]);

        return $this;
    }

    public function delete($route, $action)
    {
        $this->uri = array_merge($this->uri, [
            "DELETE:{$this->prefix}{$route}" => "{$action}",
        ]);

        return $this;
    }

    public function apiResource($route, $controller)
    {
        $this->uri = array_merge($this->uri, [
            "GET:{$this->prefix}{$route}" => "{$controller}@index",
            "GET:{$this->prefix}{$route}/{number}" => "{$controller}@show",
            "POST:{$this->prefix}{$route}" => "{$controller}@store",
            "PUT:{$this->prefix}{$route}/{number}" => "{$controller}@update",
            "DELETE:{$this->prefix}{$route}/{number}" => "{$controller}@destroy",
        ]);

        return $this;
    }
}
