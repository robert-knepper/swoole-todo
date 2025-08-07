<?php

namespace App\Shared\HttpServer\Lib\Client\Exception;

use App\Shared\HttpServer\Lib\Response\HttpStatus;

class ServerNotfoundException extends \Exception
{
    public function __construct(
        $message = "server not found error",
        $code = HttpStatus::NOT_FOUND,
        \Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}