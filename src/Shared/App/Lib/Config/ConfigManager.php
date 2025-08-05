<?php

namespace App\Shared\App\Lib\Config;

class ConfigManager
{
    private array $configByPrefix;

    public function __construct(string $defaultConfigPath)
    {
        $this->configByPrefix = [];
        $this->registerConfig($defaultConfigPath,'default');
    }

    public function registerConfig($configPath, string $prefix): void
    {
        $this->configByPrefix[$prefix] = require_once $configPath;
    }

    public function get(string $key, string $prefix = 'default'): mixed
    {
        return $this->configByPrefix[$prefix][$key];
    }
}