<?php

namespace App\Task\Infrastructure\Adapter;

use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Domain\Task;
use Swoole\Database\RedisPool;

class RedisTaskRepositoryAdapter implements TaskRepositoryPort
{

    public function __construct(private RedisPool $redisPool)
    {
    }

    public function save(Task $task): void
    {
        $redis = $this->redisPool->get();

        $key = 'tasks.' . $task->id;
        $redis->set($key, json_encode($task), ['EX' => 60]); // by seconds.
        assert($redis->get($key) === 'dummy', 'The value stored in Redis should be "dummy".');
        $this->redisPool->put($redis);
        $this->tasks[$task->id] = $task;
    }

    public function findById(int $id): ?Task
    {
        $redis = $this->redisPool->get();
        $key = 'tasks.' . $id;
        $itemStr = $redis->get($key);
        $this->redisPool->put($redis);
        return $itemStr === null ? null : Task::makeFromArr(json_decode($itemStr,true));
    }

    public function all(): array
    {
        $redis = $this->redisPool->get();

        $tasks = [];
        $keys = $redis->keys('tasks.*');

        foreach ($keys as $key) {
            $itemStr = $redis->get($key);
            if ($itemStr !== null) {
                $tasks[] = Task::makeFromArr(json_decode($itemStr, true));
            }
        }

        $this->redisPool->put($redis);

        return $tasks;
    }
}