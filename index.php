<?php
use \App\Utils\Routing\Router;

require "vendor/autoload.php";


$router = new Router();

$router->setRoute(
    "get",
    '/',
    '\App\Controllers\HomeController::index'
);


$router->start();