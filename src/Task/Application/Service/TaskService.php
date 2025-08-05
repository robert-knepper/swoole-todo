<?php

namespace App\Task\Application\Service;

use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Domain\Task;
use App\User\Application\Dto\UserDto;
use Swoole\Http\Request;
use Swoole\Http\Response;

class TaskService
{
    public function __construct(private TaskRepositoryPort $taskRepositoryPort)
    {
    }

    public function createTask(Request $request, Response $response)
    {
        $task = new Task(
            rand(1200, 90000),
            $request->post['title'] ?? '',
            $request->post['description'] ?? '',
            false,
            time()
        );
        $this->taskRepositoryPort->save($task);
        $response->end(json_encode($task->toArray()));
    }

    public function getTask(Request $request, Response $response)
    {
        $task = $this->taskRepositoryPort->findById($request->get['id']);
        if ($task != null){
            $response->end(json_encode($task->toArray()));
        }

        $response->end(json_encode([]));
    }

    public function getAllTasks(Request $request, Response $response)
    {
        $tasks = $this->taskRepositoryPort->all();
        $response->end(json_encode($tasks));
    }
}