<?php

namespace App\Shared\HttpServer\Lib\Test;

trait CoroutineTest
{
    protected function runTestOnCoroutine(callable $callback)
    {
        $exception = null;
        \Swoole\Coroutine\run(function () use (&$exception, $callback) {
            try {
                $callback();
            } catch (\Throwable $e) {
                $exception = $e;
            }
        });

        if ($exception) {
            throw $exception;
        }
    }
}