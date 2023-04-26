<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Utils\Authorisation;
use App\Utils\Routing\Router;

class AuthController
{
    public static function login()
    {
        $body = Router::getReq()->getJSONBody();

        if (!isset($body['login']) or !isset($body['password']))
            throw new \Exception("Request body should be containing 'login' and 'password' fields", 400);

        $model = new UsersModel();

        $hash = $model->getUserHashedPassword($body['login']);
        if (password_verify($body['password'], $hash))
            Router::getRes()->sendJson(["token" => Authorisation::createToken($body['login'])]);
        else
            throw new \Exception('Wrong login or password', 401); // https://stackoverflow.com/a/32752617
    }

    public static function register()
    {
        $body = Router::getReq()->getJSONBody();

        if (!isset($body['login']) or !isset($body['password']))
            throw new \Exception("Request body should be containing 'login' and 'password' fields", 400);


        $model = new UsersModel();

        if (!$model->loginExists($body['login']))
        {
            $model->addNewUser($body['login'], $body['password']);
            self::login();
        }
        else
            throw new \Exception("Login is already taken", 401);
    }
}
