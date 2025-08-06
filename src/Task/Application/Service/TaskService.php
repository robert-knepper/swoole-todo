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

    public function createTask(Request $request)
    {
        if (!(isset($request->post['title'])
            && is_string($request->post['title'])
            && strlen($request->post['title']) < 150
            && strlen($request->post['title']) != 0))
            return $this->errorWithMessage('title is not valid', HttpStatus::BAD_REQUEST);

        if (!(isset($request->post['description'])
            && is_string($request->post['description'])
            && strlen($request->post['description']) < 500
            && strlen($request->post['description']) != 0))
            return $this->errorWithMessage('description is not valid', HttpStatus::BAD_REQUEST);

        $task = new Task(
            rand(1200, 90000),
            $request->post['title'] ?? '',
            $request->post['description'] ?? '',
            false,
            time()
        );

        $this->taskRepositoryPort->save($task);
        return $this->successWithData($task->toArray(), HttpStatus::CREATED);
    }

    public function getTask(Request $request)
    {
        if (!(isset($request->get['id']) && is_numeric($request->get['id'])))
            return $this->errorWithMessage('id param not valid', HttpStatus::BAD_REQUEST);

        $task = $this->taskRepositoryPort->findById($request->get['id']);
        if ($task === null)
            return $this->error(HttpStatus::NOT_FOUND);

        return $this->successWithData($task->toArray());
    }

    public function getAllTasks(Request $request)
    {
        $tasks = $this->taskRepositoryPort->all();
        $this->successWithData($tasks);
    }
}