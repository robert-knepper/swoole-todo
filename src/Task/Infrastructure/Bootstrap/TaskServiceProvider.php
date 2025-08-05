<?php

namespace App\Task\Infrastructure\Bootstrap;

use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\HttpServer\Lib\Router;
use App\Shared\Mtproto\TLClassStore;
use App\Shared\Mtproto\TLObject;
use App\Task\Application\Service\TaskService;
use App\Task\Application\Service\TaskServiceMtproto;
use App\Task\Infrastructure\Adapter\InMemoryTaskRepositoryAdapter;
use App\Task\Infrastructure\Adapter\RedisTaskRepositoryAdapter;
use App\Task\Infrastructure\Mtproto\TL_task_createTask;
use App\Task\Infrastructure\Mtproto\TL_task_Task;
use Swoole\Database\RedisPool;

class TaskServiceProvider extends BaseServiceProvider
{

    public function register(): void
    {
        $this->registerService();
        $this->registerRoutes($this->container->getRouter());
    }

    private function registerService() : void
    {
        $this->container->bind(InMemoryTaskRepositoryAdapter::class, new InMemoryTaskRepositoryAdapter());
        $this->container->bind(
            RedisTaskRepositoryAdapter::class,
            new RedisTaskRepositoryAdapter($this->container->get(RedisPool::class))
        );
        $this->container->bind(TaskService::class, new TaskService($this->container->get(RedisTaskRepositoryAdapter::class)));
    }

    private function registerRoutes(Router $router) : void
    {
        $router->add('POST', '/task', [TaskService::class,'createTask']);
        $router->add('GET', '/task', [TaskService::class,'getTask']);
        $router->add('GET', '/task/all', [TaskService::class,'getAllTasks']);
    }

}