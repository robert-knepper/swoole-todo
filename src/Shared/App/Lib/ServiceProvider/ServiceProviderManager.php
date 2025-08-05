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

    public function run(): void
    {
        // register
        foreach ($this->providers as $provider) {
            $provider->register();
        }

        // boot
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }
}