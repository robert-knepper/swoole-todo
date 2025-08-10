<?php

namespace App\Shared\App\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;

class AppServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        $this->container
            ->registerHelperFunction()
            ->loadFilePath(__DIR__ . '/../../Support/helpers.php');

        $this->container
            ->getConfigManager()
            ->registerConfig('app',__DIR__ . '/../../config.php');
    }

}