<?php

namespace App\Task\Domain;

use App\Shared\HttpServer\Lib\Response\Arrayable;

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

}