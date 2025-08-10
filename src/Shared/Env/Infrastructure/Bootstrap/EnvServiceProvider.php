<?php

namespace App\Shared\Env\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;

class EnvServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        $envName = isset($envName) ? $envName : null; // override while test run
        $dotenv = \Dotenv\Dotenv::createImmutable(APP->getRootPath() . '/',$envName);
        $dotenv->load();
    }

}