<?php

namespace App\Utils\Routing\Middleware;

class Middleware
{
    private static $registeredMiddleware = [
        'testing' => '\App\Utils\Routing\Middleware\testing'
    ];

    private static $globalMiddleware = [    // these middlewares will be applied to every single request
        'testing'
    ];

    public static function getGlobalMiddleware() : array
    {
        return self::$globalMiddleware;
    }

    public static function getRegisteredMiddleware() : array
    {
        return self::$registeredMiddleware;
    }
}