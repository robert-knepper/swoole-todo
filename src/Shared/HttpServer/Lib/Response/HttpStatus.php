<?php

namespace App\Shared\HttpServer\Lib\Response;

class HttpStatus
{
    // 2xx Success
    public const OK = 200;
    public const CREATED = 201;
    public const NO_CONTENT = 204;

    // 4xx Client Error
    public const BAD_REQUEST = 400;
    public const UNAUTHORIZED = 401;
    public const FORBIDDEN = 403;
    public const NOT_FOUND = 404;
    public const UNPROCESSABLE_ENTITY = 422;

    // 5xx Server Error
    public const INTERNAL_SERVER_ERROR = 500;
    public const SERVICE_UNAVAILABLE = 503;

    protected static array $labels = [
        self::OK => 'OK',
        self::CREATED => 'Created',
        self::NO_CONTENT => 'No Content',

        self::BAD_REQUEST => 'Bad Request',
        self::UNAUTHORIZED => 'Unauthorized',
        self::FORBIDDEN => 'Forbidden',
        self::NOT_FOUND => 'Not Found',
        self::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',

        self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
        self::SERVICE_UNAVAILABLE => 'Service Unavailable',
    ];

    public static function label(int $code): string
    {
        return self::$labels[$code];
    }
}