<?php

namespace App\Task\Application\Service;

use App\Shared\HttpServer\Lib\Response\HttpDefaultResponse;
use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Domain\Task;
use App\User\Application\Dto\UserDto;
use Swoole\Http\Request;
use Swoole\Http\Response;

class TaskService
{
    use HttpDefaultResponse;

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
        if (!(isset($request->get['id']) && is_numeric($request->get['id'])))
            return $this->errorWithMessage('id param not valid', HttpStatus::BAD_REQUEST, $response);

        $task = $this->taskRepositoryPort->findById($request->get['id']);
        if ($task === null)
            return $this->error(HttpStatus::NOT_FOUND, $response);

        return $this->successWithData($task->toArray(), $response);
    }

    public function getAllTasks(Request $request, Response $response)
    {
        $tasks = $this->taskRepositoryPort->all();
        $response->end(json_encode($tasks));
    }
}