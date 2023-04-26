<?php

use \App\Utils\Routing\Router;

require "../vendor/autoload.php";

require "../src/Utils/Constants.php";

header('Content-Type: application/json');

if (\App\Config::prod == 1)
    ini_set('display_errors', 'Off');


\Dotenv\Dotenv::createImmutable(__DIR__ . "/..")->load();

set_exception_handler([\App\Utils\Errors::class, 'exceptionHandler']);
register_shutdown_function([\App\Utils\Errors::class, 'fatalErrorHandler']);


Router::add(
    [AnyMethod],
    '/login/',
    [\App\Controllers\AuthController::class, 'login']
);

Router::add(
    [AnyMethod],
    "/register/",
    [\App\Controllers\AuthController::class, 'register']
);

Router::add(
    ['get'],
    '/posts/{id}/',
    [\App\Controllers\PostsController::class, 'getPostById'],
    [
        'where' => [
            'id' => '[0-9]+'
        ]
    ]
);

Router::add(
    ['put'],
    '/posts/',
    [\App\Controllers\PostsController::class, 'createPost'],
    [
        'middleware' => ['auth']
    ]
);

Router::add(
    ['delete'],
    '/posts/{id}',
    [\App\Controllers\PostsController::class, 'deletePost'],
    [
        'where' => [
            'id' => '[0-9]+'
        ],
        'middleware' => ['auth']
    ]
);

Router::add(
    ['patch'],
    '/posts/{id}',
    [\App\Controllers\PostsController::class, 'updatePost'],
    [
        'where' => [
            'id' => '[0-9]+'
        ],
        'middleware' => ['auth']
    ]
);

Router::add(
    ['get'],
    '/posts/',
    [\App\Controllers\PostsController::class, 'getAllPosts']
);

Router::start();
