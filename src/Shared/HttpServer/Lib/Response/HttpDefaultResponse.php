<?php

namespace App\Shared\HttpServer\Lib\Response;

use Swoole\Http\Response;

trait HttpDefaultResponse
{
    protected function error(int $statusCode, Response $response, array $data = [])
    {
        $response->status($statusCode);
        $response->setHeader('Content-Type', 'application/json');
        $response->end(json_encode([
            'data' => $data,
            'message' => HttpStatus::label($statusCode),
            'status' => $statusCode,
            'success' => false,
        ]));
    }

    protected function errorWithMessage(string $message, int $statusCode, Response $response, array $data = [])
    {
        $response->status($statusCode);
        $response->setHeader('Content-Type', 'application/json');
        $response->end(json_encode([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
            'success' => false,
        ]));
    }
}