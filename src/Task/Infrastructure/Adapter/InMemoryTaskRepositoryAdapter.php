<?php
namespace App\Task\Infrastructure\Adapter;

use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Domain\Task;

class InMemoryTaskRepositoryAdapter implements TaskRepositoryPort {
    private array $tasks = [];


    public function save(Task $task): void
    {
        $task->id = rand(1200, 90000);
        $this->tasks[$task->id] = $task;
    }

    public function findById(int $id): ?Task
    {
       return $this->tasks[$id] ?? null;
    }

    public function all(): array
    {
        return $this->tasks;
    }

    public function truncate(): void
    {
        $this->tasks = [];
    }

    public function remove(int $id): void
    {
        unset($this->tasks[$id]);
    }

    public function count(): int
    {
        return count($this->tasks);
    }

    public function update(Task $task): void
    {
        $this->tasks[$task->id] = $task;
    }
}