<?php

namespace App\Utils;

use PDO;

class DB
{
    public static function Connection() : PDO
    {
        $host = $_ENV['db_host'];
        $db   = $_ENV['db_name'];
        $user = $_ENV['db_user'];
        $pass = $_ENV['db_pass'];
        $charset = 'utf8';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new PDO($dsn, $user, $pass, $opt);
    }
}