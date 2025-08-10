<?php

if (!function_exists('app')) {
    function app(): \App\Shared\App\Lib\App
    {
        return APP;
    }
}

if (!function_exists('config')) {
    function config(string $prefix, string $key): mixed
    {
        return APP->getConfigManager()->get($key, $prefix);
    }
}

if (!function_exists('root_path')) {
    function root_path(): string
    {
        return APP->getRootPath();
    }
}

if (!function_exists('storage_path')) {
    function storage_path(): string
    {
        return APP->getRootPath() . 'storage';
    }
}

