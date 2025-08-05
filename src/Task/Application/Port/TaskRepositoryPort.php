<?php
namespace App\Task\Application\Port;
use App\Task\Domain\Task;

interface TaskRepositoryPort {
    public function save(Task $task): void;
    public function findById(int $id): ?Task;
    public function all(): array;
}