<?php

namespace App\Shared\Log\Lib;

abstract class Logger
{

    abstract protected function log(string $level, string $message): void;

    protected function format(string $level, string $message): string
    {
        $timestamp = date('Y-m-d H:i:s');
        return "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
    }

    public function debug(string $message): void
    {
        $this->log('debug', $message);
    }

    public function info(string $message): void
    {
        $this->log('info', $message);
    }

    public function warning(string $message): void
    {
        $this->log('warning', $message);
    }

    public function error(string $message): void
    {
        $this->log('error', $message);
    }

}