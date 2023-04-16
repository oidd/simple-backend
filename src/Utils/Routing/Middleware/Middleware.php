<?php

namespace App\Utils\Routing\Middleware;

class Middleware
{
    private static $registeredMiddleware = [
        'testing' => \App\Utils\Routing\Middleware\Testing::class
    ];

    private static $globalMiddleware = [    // these middlewares will be applied to every single request
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