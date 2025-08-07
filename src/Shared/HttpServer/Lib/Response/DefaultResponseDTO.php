<?php

namespace App\Shared\HttpServer\Lib\Response;

class DefaultResponseDTO implements Arrayable
{

    public function __construct(
        private array  $data,
        private int    $code,
        private bool   $success,
        private string $message,
    )
    {
    }

    public function toArray()
    {
        return [
            'data' => $this->data,
            'code' => $this->code,
            'success' => $this->success,
            'message' => $this->message
        ];
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}