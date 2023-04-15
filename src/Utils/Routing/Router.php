<?php

namespace App\Utils\Routing;

use App\Utils\Routing\Middleware\Middleware;

class Router
{
    private $routes = [];

    const Methods = [
        'get',
        'post',
        'patch',
        'delete'
    ];

    public function __construct()
    {
        foreach (self::Methods as $m)
            $this->routes[$m] = [];
    }

    public function setRoute(string $method, string $route, string $controller, array $middleware = [])
    {
        if (!in_array($method, self::Methods))
            throw new \Exception("Method is not supported");

        foreach ($this->routes[$method] as $r)
            if ($r['route'] == $route)
                throw new \Exception("This route already specified");

        $this->routes[$method][] = [
            'route' => $route,
            'controller' => $controller,
            'middleware' => $middleware
        ];
    }

    public function start()
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        $URI = $_SERVER['REQUEST_URI'];


        foreach ($this->routes[$method] as $r)
        {
            if ($r['route'] == $URI)
            {
                $route = $r;
                break;
            }
        }

        if (!isset($route))
            throw new \Exception("This route is not specified");

        foreach (Middleware::getGlobalMiddleware() as $k)
            call_user_func([Middleware::getRegisteredMiddleware()[$k], 'run']);

        if (!empty($route['middleware']))
            foreach ($route['middleware'] as $k)
                call_user_func([Middleware::getRegisteredMiddleware()[$k], 'run']);


        call_user_func($route['controller']);
    }
}