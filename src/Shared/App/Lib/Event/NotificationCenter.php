<?php

namespace App\Shared\App\Lib\Event;

use function PHPUnit\Framework\isArray;

class NotificationCenter
{
    protected array $listeners = [];

    /**
     * @param string $event
     * @param array $listener [Controller::class,'action']
     * @return void
     */
    public function addObserver(string $event, array $listener): void {
        if (!isArray($this->listeners[$event]))
            $this->listeners[$event] = [];
        $this->listeners[$event][] = $listener;
    }

    public function postNotification(string $event, $data): void {
        foreach ($this->listeners[$event] ?? [] as $listener) {
            APP->get($listener[0])->{$listener[1]}($data);
        }
    }
}