<?php

namespace App\Controllers;

use App\Models\PostsModel;
use App\Models\UsersModel;
use App\Utils\Authorisation;
use App\Utils\Routing\Router;

class PostsController
{
    public static function getPostById($id)
    {
        $model = new PostsModel();

        if ($model->isPostExists($id))
            Router::getRes()->sendJson($model->getPostById($id));
        else
            throw new \Exception("No post found with this id", 404);
    }

    public static function createPost()
    {
        if (($c = Router::getReq()->getJSONBody()["contents"]) === NULL)
            throw new \Exception("Request body should contain 'contents' field", 400);

        $model = new PostsModel();

        Router::getRes()->sendJson($model->createPost(Authorisation::$userName, $c));
    }

    public static function deletePost($id)
    {
        $model = new PostsModel();

        if ($model->isPostExists($id))
            Router::getRes()->sendJson($model->deletePost($id));
        else
            throw new \Exception("No post found with this id", 404);
    }

    public static function updatePost($id)
    {
        $model = new PostsModel();

        $body = Router::getReq()->getJSONBody();

        if (!isset($body['contents']))
            throw new \Exception("Request body should be containing 'contents' field", 400);

        if ($model->isPostExists($id))
            Router::getRes()->sendJson($model->updatePost($id, $body['contents']));
        else
            throw new \Exception("No post found with this id", 404);
    }

    public static function getAllPosts()
    {
        $model = new PostsModel();

        Router::getRes()->sendJson($model->getAllPosts());
    }
}