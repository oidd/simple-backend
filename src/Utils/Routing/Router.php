<?php

namespace App\Utils\Routing;

use App\Utils\Routing\http\Request;
use App\Utils\Routing\http\Response;
use App\Utils\Routing\Middleware\Middleware;

class Router
{
    private static array $routes = [];

    private static Request $req;
    private static Response $res;

    public static function getReq(): Request
    {
        return self::$req;
    }

    public static function getRes(): Response
    {
        return self::$res;
    }

    public static function add(array $method, string $route, $callback, array $settings = [])
    {
        $r = new Route($route, $method, $callback, $settings);
        if (isset($settings['middleware']))
            $r->setMiddleware($settings['middleware']);
        self::$routes[] = $r;
    }

    public static function start()
    {
        self::$req = new Request();

        self::$res = new Response(self::$req);

        $method = self::$req->getMethod();
        $URI = self::$req->getURI();

        foreach (self::$routes as $r)
            if ($r->match($URI, $method))
                $route = $r;

        if (!isset($route))
            throw new \Exception("This route is not specified", 404);

        foreach (Middleware::getGlobalMiddleware() as $k)
            if (in_array($k, Middleware::getRegisteredMiddleware()))
                call_user_func([Middleware::getRegisteredMiddleware()[$k], 'run']);

        $route->proceed($URI);
    }
}
