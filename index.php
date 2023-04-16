<?php

use App\Utils\Constants;
use \App\Utils\Routing\Router;

require "vendor/autoload.php";

header('Content-Type: application/json');

Router::add(
    [Constants::AnyMethod],
    '/posts/{id}/{someitem}',
    [\App\Controllers\HomeController::class, 'index'],
    [
        'where' => [
            'id' => '[0-9]+',
            'someitem' => '\w+'
        ],
        'middleware' => ['testing']
    ]
);


Router::start();