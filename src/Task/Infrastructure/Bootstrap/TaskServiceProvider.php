<?php

namespace App\Task\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\HttpServer\Lib\Router;
use App\Task\Application\Command\CreateTaskCommand;
use App\Task\Application\Command\DeleteTaskCommand;
use App\Task\Application\Command\GetAllTaskCommand;
use App\Task\Application\Command\GetTaskCommand;
use App\Task\Application\Command\UpdateTaskCommand;
use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Application\Service\TaskService;
use App\Task\Infrastructure\Adapter\InMemoryTaskRepositoryAdapter;
use App\Task\Infrastructure\Adapter\RedisTaskRepositoryAdapter;

class TaskServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        $this->registerCommands();
        $this->registerService();
        $this->registerRoutes($this->container->getRouter());
    }

    private function registerCommands(): void
    {
        $this->container->registerCommand()->add(CreateTaskCommand::class);
        $this->container->registerCommand()->add(GetTaskCommand::class);
        $this->container->registerCommand()->add(GetAllTaskCommand::class);
        $this->container->registerCommand()->add(DeleteTaskCommand::class);
        $this->container->registerCommand()->add(UpdateTaskCommand::class);
    }

    private function registerService(): void
    {
        $this->container->lazyBind(InMemoryTaskRepositoryAdapter::class, function () {
            return new InMemoryTaskRepositoryAdapter();
        });

        $this->container->lazyBind(RedisTaskRepositoryAdapter::class, function () {
            return new RedisTaskRepositoryAdapter(
                $this->container->get(\Swoole\Database\RedisPool::class)
            );
        });

        $this->container->lazyBind(TaskRepositoryPort::class, function () {
            $driver = config('app','database')['connection'];
            $class = match ($driver) {
                'redis' => RedisTaskRepositoryAdapter::class,
                'memory' => InMemoryTaskRepositoryAdapter::class,
                default => throw new \RuntimeException("Unsupported task_db_connection: $driver")
            };
            return $this->container->lazyGet($class);
        });
        $this->container->singleton(TaskService::class, new TaskService($this->container->lazyGet(TaskRepositoryPort::class)));
    }

    private function registerRoutes(Router $router): void
    {
        $router->add('POST', '/task', [TaskService::class, 'createTask']);
        $router->add('GET', '/task', [TaskService::class, 'getTask']);
        $router->add('GET', '/task/all', [TaskService::class, 'getAllTasks']);
        $router->add('POST', '/task/delete', [TaskService::class, 'delete']);
        $router->add('POST', '/task/update', [TaskService::class, 'update']);
    }

}