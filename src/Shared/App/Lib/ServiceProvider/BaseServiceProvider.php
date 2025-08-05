<?php

namespace App\Shared\App\Lib\ServiceProvider;

use App\Shared\App\Lib\App;

abstract class BaseServiceProvider
{

    public function __construct(protected App $container)
    {
    }

    abstract public function register(): void;
    public function boot(): void {}
}