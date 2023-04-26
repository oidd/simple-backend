<?php

namespace App\Utils\Routing;

use App\Utils\Routing\Middleware\Middleware;

class Route
{
    private array $method;
    private string $pathRegex;
    private $callback;
    private array $middleware;
    private array $params;

    /*
     * settings = [
     *      'where' => ['param_name' => '[0-9]+'],              parameter (e.g. {id}) clarification
     *      'middleware' => ['auth']                            middleware aliases that registered in Middleware.php
     * ]
     */

    //$callback – string or array related to static function
    public function __construct(string $path, array $method, callable $callback, array $settings = [])
    {
        if ($path[-1] != '/')   //check if path have slash as the last symbol
            $path .= '/';

        $this->callback = $callback;

        if (isset($settings['middleware']))
            $this->middleware = $settings['middleware'];
        else
            $this->middleware = [];

        list ($this->pathRegex, $this->params) = $this->resolveRegEx($path, $settings['where']);

        $this->method = $method;
    }

    public function setMiddleware(array $middleware)
    {
        $this->middleware = $middleware;
    }

    private function proceedCallback($URI)
    {
        call_user_func($this->callback, ...$this->resolveParams($URI));
    }

    private function proceedMiddleware()
    {
        foreach ($this->middleware as $k)
            call_user_func([Middleware::getRegisteredMiddleware()[$k][0], 'run']);
    }

    public function proceed($URI)
    {
        $this->proceedMiddleware();
        $this->proceedCallback($URI);
    }

    public function match(string $URI, string $method) : bool
    {
        if ($URI[-1] != '/')
            $URI .= '/';

        return preg_match($this->pathRegex, $URI)
                and (in_array($method, $this->method) or in_array(AnyMethod, $this->method));
    }

    private function resolveParams($path) : array
    {
        if ($path[-1] != '/')
            $path .= '/';

        $splitted = preg_split('/\//', $path);

        $res = [];

        foreach ($this->params as $i)
            foreach ($i as $k => $v)
                $res[] = $splitted[$v];

        return $res;
    }

    private function resolveRegEx(string $path, $where) : array
    {
        $splitted = preg_split('/\//', $path);
        $res = '/\/';
        $params = [];

        if (is_null($where))
            $where = [];

        for($i = 0; $i < count($splitted); $i++)
        {
            if (empty($splitted[$i]))
                continue;

            if (preg_match('/{([^}]*)}/', $splitted[$i], $tmp)) {
                if (in_array($tmp[1], array_keys($where)))
                    $res .= $where[$tmp[1]] . '\/';
                else
                    $res .= '[A-Za-z0-9]+' . '\/';

                $params[] = [$tmp[1] => $i];
            }
            else
                $res .= $splitted[$i] . '\/';
        }

        $res .= '\z/';
        return [$res, $params];
    }
}
