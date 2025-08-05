<?php

namespace App\Shared\HttpServer\Lib;

use App\Shared\App\Lib\App;
use Swoole\Http\Request;
use Swoole\Http\Response;

class Router
{
    public function __construct(private App $app)
    {
    }

    /**
     * @var array<string, array<string, callable>>
     */
    private array $routes = [];

    public function add(string $method, string $path, array $handler): void
    {
        $method = strtoupper($method);
        $this->routes[$method][$path] = $handler;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function dispatch(Request $request, Response $response): void
    {
        $method = strtoupper($request->server['request_method'] ?? 'GET');
        $path = $request->server['request_uri'] ?? '/';

        $handler = $this->routes[$method][$path] ?? null;

        if ($handler === null) {
            $response->status(404);
            $response->end("404 Not Found");
            return;
        }
        $this->app->get($handler[0])->{$handler[1]}($request, $response);
    }
}