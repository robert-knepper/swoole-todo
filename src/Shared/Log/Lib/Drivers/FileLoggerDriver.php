<?php

namespace App\Shared\Log\Lib\Drivers;

use App\Shared\Log\Lib\Logger;

class FileLoggerDriver extends Logger
{
    private string $logFilePath;
    private array $buffer = [];
    private int $bufferLimit = 10;

    function __construct()
    {
        $this->logFilePath = storage_path() . config('log_dir', 'log') . '/app.log';
        $this->ensureExistDir();
        register_shutdown_function([$this, 'flush']);
    }

    private function ensureExistDir(): void
    {
        $logDirectory = dirname($this->logFilePath);
        if (file_exists($logDirectory))
            return;
        mkdir($logDirectory, 0755, true);
    }

    protected function log(string $level, string $message): void
    {
        $logLine = $this->format($level, $message);
        $this->buffer[] = $logLine;

        if (count($this->buffer) >= $this->bufferLimit)
            $this->flush();
    }

    public function flush(): void
    {
        if (empty($this->buffer)) return;
        file_put_contents($this->logFilePath, implode('', $this->buffer), FILE_APPEND | LOCK_EX);
        $this->buffer = [];
    }

    /**
     * call this method from job in scheduler
     * @return void
     */
    public function chunkLogFileIfIsBigger(): void
    {
        // todo : check log size if > 5M --> change name [app.log] to [archive.date.app.log]
        // todo : if count file > 20 --> remove old log archive
    }

}