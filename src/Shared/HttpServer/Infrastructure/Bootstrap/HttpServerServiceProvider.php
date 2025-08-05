<?php

namespace App\Shared\HttpServer\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\HttpServer\Application\Command\RouteListCommand;
use App\Shared\HttpServer\Application\Command\StartServerCommand;
use App\Shared\HttpServer\Lib\HttpServer;

class HttpServerServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->container->getRegisterCommand()->add(RouteListCommand::class);
        $this->container->getRegisterCommand()->add(StartServerCommand::class);
    }

}