<?php

namespace App\Shared\Log\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\Log\Lib\Drivers\FileLoggerDriver;
use App\Shared\Log\Lib\Drivers\MemoryLoggerDriver;

class LogServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        app()->getConfigManager()->registerConfig('log', __DIR__ . '/../../config.php');
        app()->registerHelperFunction()->loadFilePath(__DIR__ . '/../../Support/helpers.php');

        app()->lazyBind('file_log', function () {
            return new FileLoggerDriver();
        });

        app()->lazyBind('memory_log', function () {
            return new MemoryLoggerDriver();
        });

        app()->singleton('logger', app()->lazyGet(config('log', 'driver')));
    }

}