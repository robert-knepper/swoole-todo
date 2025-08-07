<?php

namespace App\Task\Infrastructure\Bootstrap;

use App\Shared\App\Lib\Console\RegisterCommand;
use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\HttpServer\Lib\Router;
use App\Shared\Mtproto\TLClassStore;
use App\Shared\Mtproto\TLObject;
use App\Task\Application\Command\CreateTaskCommand;
use App\Task\Application\Command\DeleteTaskCommand;
use App\Task\Application\Command\GetAllTaskCommand;
use App\Task\Application\Command\GetTaskCommand;
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
        $this->registerCommands();
        $this->registerService();
        $this->registerRoutes($this->container->getRouter());
    }

    private function registerCommands() : void
    {
        $this->container->registerCommand()->add(CreateTaskCommand::class);
        $this->container->registerCommand()->add(GetTaskCommand::class);
        $this->container->registerCommand()->add(GetAllTaskCommand::class);
        $this->container->registerCommand()->add(DeleteTaskCommand::class);
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
        $router->add('POST', '/task/delete', [TaskService::class,'delete']);
        $router->add('POST', '/task/update', [TaskService::class,'update']);
    }

}