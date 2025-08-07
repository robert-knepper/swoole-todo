<?php

namespace App\Shared\HttpServer\Lib\Response;

interface Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array<TKey, TValue>
     */
    public function toArray();
}