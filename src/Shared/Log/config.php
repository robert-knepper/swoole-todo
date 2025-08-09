<?php
return [
    /**
     * drivers:
     * - file_log
     * - memory_log
     */
    'driver' => env('LOG_DRIVER', 'file_log'),
    'log_dir' => env('LOG_DIR', '/tmp/log'),
];