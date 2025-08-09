<?php

namespace App\Shared\Redis\Lib;

use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool;

class RedisPoolMaker
{
    public static function make($host, $port, $pass, $db = 0, $countConnection = 32)
    {
        try {
            $config = (new RedisConfig())
                ->withHost($host)
                ->withPort($port)
                ->withAuth($pass)
                ->withDbIndex($db);

            return new RedisPool($config, 32);
        } catch (\Exception $exception) {
            logger()->error($exception->getMessage());
            throw $exception;
        }
    }
}