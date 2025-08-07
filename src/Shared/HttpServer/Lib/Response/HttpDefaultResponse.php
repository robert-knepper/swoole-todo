<?php

namespace App\Shared\HttpServer\Lib\Response;

trait HttpDefaultResponse
{
    protected function error(int $statusCode, array $data = []): DefaultResponseDTO
    {
        return new DefaultResponseDTO(
            $data,
            $statusCode,
            false,
            HttpStatus::label($statusCode)
        );
    }

    protected function success(int $statusCode = 200, array $data = []): DefaultResponseDTO
    {
        return new DefaultResponseDTO(
            $data,
            $statusCode,
            true,
            HttpStatus::label($statusCode)
        );
    }

    protected function errorWithMessage(string $message, int $statusCode, array $data = []): DefaultResponseDTO
    {
        return new DefaultResponseDTO(
            $data,
            $statusCode,
            false,
            $message
        );
    }

    protected function successWithData(array $data, int $statusCode = 200): DefaultResponseDTO
    {
        return new DefaultResponseDTO(
            $data,
            $statusCode,
            true,
            HttpStatus::label($statusCode)
        );
    }


}