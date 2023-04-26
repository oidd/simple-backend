<?php

namespace App\Utils;

use App\Config;
use App\Utils\Routing\Router;

class Errors
{
    // не используем объекты Request и Response в этом классе, так как не можем гарантировать, что к моменту появления исключения соответствующие поля были определены

    public static function exceptionHandler(\Throwable $e)
    {
        self::errorLog($e);
        if ($e->getCode() <= 499 & $e->getCode() >= 400)
        {
            header("HTTP/1.1 {$e->getCode()}");

            echo json_encode([
                "error_message" => $e->getMessage()
            ]);
        }
        else
        {
            header("HTTP/1.1 500");

            echo json_encode([
                "error_message" => "Internal server error"
            ]);
        }
    }

    public static function fatalErrorHandler()
    {
        $e = error_get_last();
        if (isset($e) and $e['type'] === E_ERROR)
        {
            self::errorLog($e['message']);
            echo json_encode([
                "error_message" => "Internal server error"
            ]);
        }
    }

    private static function errorLog($e)
    {
        if (!file_exists(Config::errorLogPath))
            mkdir(Config::errorLogPath);

        $filename = date("Y-m-d");

        $stream = fopen(Config::errorLogPath . $filename, 'a+');

        $data = date("[H:i:s] ") . $_SERVER['REQUEST_METHOD'] . $_SERVER['REQUEST_URI'] . " – " . $e . "\n\n";

        fwrite($stream, $data);
     }
}
