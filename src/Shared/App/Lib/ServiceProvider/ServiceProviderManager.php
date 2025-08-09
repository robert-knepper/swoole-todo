<?php

namespace App\Shared\App\Lib\ServiceProvider;

class ServiceProviderManager
{
    /**
     * @var BaseServiceProvider[]
     */
    private array $providers = [];

    public function addProvider(BaseServiceProvider $provider): void
    {
        $this->providers[] = $provider;
    }

    public function runRegister(): void
    {
        foreach ($this->providers as $provider) {
            $provider->register();
        }
    }

    public function runBoot(): void
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }
}