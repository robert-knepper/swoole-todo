<?php
return [
    'providers' => [
        \App\Shared\App\Infrastructure\Bootstrap\AppServiceProvider::class,
        \App\Shared\Redis\Infrastructure\Bootstrap\RedisServiceProvider::class,
        \App\Task\Infrastructure\Bootstrap\TaskServiceProvider::class,
        \App\Shared\HttpServer\Infrastructure\Bootstrap\HttpServerServiceProvider::class,
    ]
];