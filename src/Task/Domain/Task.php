<?php

namespace App\Task\Domain;

use App\Shared\HttpServer\Lib\Response\Arrayable;
use App\Task\Infrastructure\Mtproto\TL_task_Task;

class Task implements Arrayable
{
    public function __construct(
        public int    $id,
        public string $title,
        public string $description,
        public bool   $isDone,
        public int    $createdAt,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'isDone' => $this->isDone,
            'createdAt' => $this->createdAt,
        ];
    }

    public static function makeFromArr(array $item): self
    {
        return new self(
            $item['id'],
            $item['title'],
            $item['description'],
            $item['isDone'],
            $item['createdAt']
        );
    }

    public function toTLTask(): TL_task_Task
    {
        $obj = new TL_task_Task();
        $obj->id = $this->id;
        $obj->title = $this->title;
        $obj->description = $this->description;
        $obj->isDone = $this->isDone;
        $obj->createdAt = $this->createdAt;
        return $obj;
    }

    public static function tlTaskToTask(TL_task_Task $TL_task_Task): self
    {
        return new Task(
            $TL_task_Task->id,
            $TL_task_Task->title,
            $TL_task_Task->description,
            $TL_task_Task->isDone,
            $TL_task_Task->createdAt
        );
    }
}