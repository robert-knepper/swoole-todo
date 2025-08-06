<?php

namespace App\Shared\HttpServer\Lib\Response;

use Swoole\Http\Response;

trait HttpDefaultResponse
{
    protected function error(int $statusCode, array $data = []): array
    {
        return [
            'data' => $data,
            'message' => HttpStatus::label($statusCode),
            'code' => $statusCode,
            'success' => false,
        ];
    }

    protected function success(int $statusCode = 200, array $data = []): array
    {
        return [
            'data' => $data,
            'message' => HttpStatus::label($statusCode),
            'code' => $statusCode,
            'success' => true,
        ];
    }

    protected function errorWithMessage(string $message, int $statusCode, array $data = []): array
    {
        return [
            'data' => $data,
            'message' => $message,
            'code' => $statusCode,
            'success' => false,
        ];
    }

    protected function successWithData(array $data, int $statusCode = 200): array
    {
        return [
            'data' => $data,
            'message' => HttpStatus::label($statusCode),
            'code' => $statusCode,
            'success' => true,
        ];
    }


}