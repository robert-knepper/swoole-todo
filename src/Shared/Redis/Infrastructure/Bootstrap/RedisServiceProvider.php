<?php

namespace App\Shared\Redis\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use Swoole\Database\RedisConfig;
use Swoole\Database\RedisPool;

class RedisServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        $this->bindRedisPool();
    }

    private function bindRedisPool()
    {
        $host = env('REDIS_HOST', '127.0.0.1');
        $port = env('REDIS_PORT', 6379);
        $pass = env('REDIS_PASS', null);
        $db = env('REDIS_DB_NUM', 0);

        $config = (new RedisConfig())
            ->withHost($host)
            ->withPort($port)
            ->withAuth($pass)
            ->withDbIndex($db);

        $pool = new RedisPool($config, 32);
        $this->container->bind(RedisPool::class, $pool);
    }

}