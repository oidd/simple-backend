<?php

namespace App\Utils\Routing\Middleware;

class Testing implements IMiddleware
{
    public static function run()
    {
        echo 'hey';
    }
}