<?php

namespace App\Utils\Routing\Middleware;

use App\Utils\Authorisation;
use App\Utils\Routing\Router;

class Auth implements IMiddleware
{
    public static function run()
    {
        if (($auth = Router::getReq()->getHeaders()["Authorization"]) === null)
            throw new \Exception("Need token authorization to access this path", 401);

        if (!Authorisation::validateToken($auth))
            throw new \Exception("Invalid token", 401);
    }
}