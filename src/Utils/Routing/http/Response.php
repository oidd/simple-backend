<?php

namespace App\Utils\Routing\http;


class Response
{
    private Request $req;

    public function __construct(Request $req)
    {
        $this->req = $req;
    }

    public function setHeader(string $h)
    {
        header($h);
    }

    public function sendJson($contents, $status = 200)
    {
        echo json_encode($contents);
    }

    public function sendHTTPCode(int $code = 200)
    {
        header("HTTP/1.1 $code");
    }

}