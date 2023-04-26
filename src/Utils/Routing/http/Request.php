<?php

namespace App\Utils\Routing\http;

class Request
{
    private array $headers;
    private string $body = "";

    public function __construct()
    {
        foreach ($_SERVER as $k => $v)
            $this->headers[$k] = $v;

        if (($t = apache_request_headers()["Authorization"]) != NULL)
            $this->headers["Authorization"] = $t;

        $inp = file_get_contents("php://input");

        if ($inp)
            $this->body = $inp;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getJSONBody() : array
    {
        $b = json_decode($this->body, true);
        if ($b == NULL)
            throw new \Exception("failed to parse JSON in request body",400);
        return $b;
    }

    public function getURI() : string
    {
        return $this->headers['REQUEST_URI'];
    }

    public function getMethod() : string
    {
        return strtolower($this->headers['REQUEST_METHOD']);
    }

    public function __toString()
    {
        return "{$this->getMethod()} {$this->getURI()}";
    }
}