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
        $redis->set($key, json_encode($task), ['EX' => 200]); // by seconds.
        $this->redisPool->put($redis);
        $this->tasks[$task->id] = $task;
    }

    public function findById(int $id): ?Task
    {
        $redis = $this->redisPool->get();
        $key = 'tasks.' . $id;
        $itemStr = $redis->get($key);
        $this->redisPool->put($redis);
        return $itemStr ? Task::makeFromArr(json_decode($itemStr,true)) : null ;
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

    public function truncate(): void
    {
        $redis = $this->redisPool->get();

        $keys = $redis->keys('tasks.*');
        if (!empty($keys)) {
            $redis->del(...$keys);
        }

        $this->redisPool->put($redis);
    }

    public function remove(int $id): void
    {
        $redis = $this->redisPool->get();

        $key = 'tasks.' . $id;
        $redis->del($key);

        $this->redisPool->put($redis);
    }

    public function count(): int
    {
        $redis = $this->redisPool->get();

        $keys = $redis->keys('tasks.*');
        $count = is_array($keys) ? count($keys) : 0;

        $this->redisPool->put($redis);

        return $count;
    }

    public function update(Task $task): void
    {
        $this->save($task);
    }
}