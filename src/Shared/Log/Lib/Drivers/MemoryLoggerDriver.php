<?php

namespace App\Shared\Log\Lib\Drivers;

use App\Shared\Log\Lib\Logger;

/**
 * using while run tests
 */
class MemoryLoggerDriver extends Logger
{
    private array $logs;

    public function __construct()
    {
        $this->logs = [];
    }

    protected function log(string $level, string $message): void
    {
        $this->logs[] = $this->format($level, $message);
    }

}