<?php

namespace App\Controllers;

class HomeController
{
    public static function index($id, $itm)
    {
        echo $id . " " . $itm;
    }
}