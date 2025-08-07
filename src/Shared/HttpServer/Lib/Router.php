<?php

namespace App\Shared\HttpServer\Lib;

use App\Shared\App\Lib\App;
use App\Shared\HttpServer\Lib\Response\HttpDefaultResponse;
use App\Shared\HttpServer\Lib\Response\HttpStatus;
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

        // handle result
        $response->setHeader('Content-Type', 'application/json');
        if ($handler === null) {
            $response->status(HTTPStatus::NOT_FOUND);
            $response->end(json_encode([
                    'message' => HttpStatus::label(HTTPStatus::NOT_FOUND),
                    'code' => HTTPStatus::NOT_FOUND,
                    'success' => false,

            ]));
            return;
        }
        $result = $this->app->get($handler[0])->{$handler[1]}($request);
        $response->status($result['code']);
        dump($result);
        $response->end(json_encode($result));
    }
}