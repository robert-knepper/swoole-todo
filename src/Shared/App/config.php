<?php

return [
    'providers' => [
        // app
        \App\Shared\App\Infrastructure\Bootstrap\AppServiceProvider::class,
        \App\Shared\Env\Infrastructure\Bootstrap\EnvServiceProvider::class,
        \App\Shared\Log\Infrastructure\Bootstrap\LogServiceProvider::class,

        // modules
        \App\Shared\Redis\Infrastructure\Bootstrap\RedisServiceProvider::class,
        \App\Task\Infrastructure\Bootstrap\TaskServiceProvider::class,
        \App\Shared\HttpServer\Infrastructure\Bootstrap\HttpServerServiceProvider::class,
    ]
];