<?php

namespace App\Task\Application\Service;

use App\Shared\HttpServer\Lib\Response\DefaultResponseDTO;
use App\Shared\HttpServer\Lib\Response\HttpDefaultResponse;
use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Task\Application\Port\TaskRepositoryPort;
use App\Task\Application\Validation\TaskRequestValidator;
use App\Task\Domain\Task;
use Swoole\Http\Request;

class TaskService
{
    use HttpDefaultResponse;

    public function __construct(private TaskRepositoryPort $taskRepositoryPort)
    {
    }

    public function createTask(Request $request): DefaultResponseDTO
    {
        if (!TaskRequestValidator::isValidTitle($request->post['title'] ?? null))
            return $this->errorWithMessage('title is not valid', HttpStatus::BAD_REQUEST);

        if (!TaskRequestValidator::isValidDescription($request->post['description'] ?? null))
            return $this->errorWithMessage('description is not valid', HttpStatus::BAD_REQUEST);

        $task = new Task(
            -1,
            $request->post['title'] ?? '',
            $request->post['description'] ?? '',
            false,
            time()
        );

        $this->taskRepositoryPort->save($task);
        return $this->successWithData($task->toArray(), HttpStatus::CREATED);
    }

    public function getTask(Request $request): DefaultResponseDTO
    {
        if (!TaskRequestValidator::isValidId($request->get['id'] ?? null))
            return $this->errorWithMessage('id param not valid', HttpStatus::BAD_REQUEST);

        $task = $this->taskRepositoryPort->findById($request->get['id']);
        if ($task === null)
            return $this->error(HttpStatus::NOT_FOUND);

        return $this->successWithData($task->toArray());
    }

    public function getAllTasks(Request $request): DefaultResponseDTO
    {
        $tasks = $this->taskRepositoryPort->all();
        return $this->successWithData($tasks);
    }

    public function delete(Request $request)
    {
        if (!TaskRequestValidator::isValidId($request->post['id'] ?? null))
            return $this->errorWithMessage('id param not valid', HttpStatus::BAD_REQUEST);
        $this->taskRepositoryPort->remove($request->post['id']);
        return $this->success();
    }

    public function update(Request $request): DefaultResponseDTO
    {
        // global validation
        if (!TaskRequestValidator::isValidId($request->post['id'] ?? null))
            return $this->errorWithMessage('id param not valid', HttpStatus::BAD_REQUEST);

        if (!TaskRequestValidator::isValidTitle($request->post['title'] ?? null))
            return $this->errorWithMessage('title is not valid', HttpStatus::BAD_REQUEST);

        if (!TaskRequestValidator::isValidDescription($request->post['description'] ?? null))
            return $this->errorWithMessage('description is not valid', HttpStatus::BAD_REQUEST);

        if (!TaskRequestValidator::isValidIsDone($request->post['isDone'] ?? null))
            return $this->errorWithMessage('isDone is not valid', HttpStatus::BAD_REQUEST);

        // find validation
        $task = $this->taskRepositoryPort->findById($request->post['id']);

        if ($task === null)
            return $this->errorWithMessage('task not found', HttpStatus::NOT_FOUND);

        // update
        $task->title = $request->post['title'];
        $task->description = $request->post['description'];
        $task->isDone = (bool)$request->post['isDone'];
        $this->taskRepositoryPort->update($task);
        return $this->successWithData($task->toArray());
    }
}