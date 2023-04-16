<?php

namespace App\Utils\Routing;

use App\Utils\Routing\Middleware\Middleware;

class Router
{
    private static array $routes = [];

    public static function add(array $method, string $route, $callback, array $settings = [])
    {
        $r = new Route($route, $method, $callback, $settings);
        if (isset($settings['middleware']))
            $r->setMiddleware($settings['middleware']);
        self::$routes[] = $r;
    }

    public static function start()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $URI = $_SERVER['REQUEST_URI'];

        foreach (self::$routes as $r)
            if ($r->match($URI, $method))
                $route = $r;

        if (!isset($route))
            throw new \Exception("This route is not specified");

        foreach (Middleware::getGlobalMiddleware() as $k)
            if (in_array($k, Middleware::getRegisteredMiddleware()))
                call_user_func([Middleware::getRegisteredMiddleware()[$k], 'run']);

        $route->proceed($URI);
    }
}
