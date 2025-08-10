<?php

namespace App\Shared\App\Lib\Config;

class ConfigManager
{
    private array $configByPrefix;

    public function __construct(string $defaultConfigPath)
    {
        $this->configByPrefix = [];
        $this->registerConfig('default', $defaultConfigPath);
    }

    public function registerConfig(string $prefix, string $configPath): void
    {
        $this->configByPrefix[$prefix] = require $configPath;
    }

    public function get(string $key, string $prefix = 'default'): mixed
    {
        return $this->configByPrefix[$prefix][$key];
    }
}