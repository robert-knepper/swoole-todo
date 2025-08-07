<?php

namespace App\Shared\HttpServer\Lib;

use App\Shared\App\Lib\App;
use App\Shared\HttpServer\Lib\Response\DefaultResponseDTO;
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

    public function dispatch(Request $request, Response $response): DefaultResponseDTO
    {
        $method = strtoupper($request->server['request_method'] ?? 'GET');
        $path = $request->server['request_uri'] ?? '/';

        $handler = $this->routes[$method][$path] ?? null;

        // handle result
        $response->setHeader('Content-Type', 'application/json');
        if ($handler === null) {
            $response->status(HTTPStatus::NOT_FOUND);
            $responseDTO = new DefaultResponseDTO(
                [],
                HTTPStatus::NOT_FOUND,
                false,
                HttpStatus::label(HTTPStatus::NOT_FOUND)
            );
            $response->end(json_encode($responseDTO->toArray()));
            return $responseDTO;
        }

        /**
         * @var DefaultResponseDTO $result
         */
        $result = $this->app->get($handler[0])->{$handler[1]}($request);
        $response->status($result->getCode());
        $response->end(json_encode($result->toArray()));
        return $result;
    }
}