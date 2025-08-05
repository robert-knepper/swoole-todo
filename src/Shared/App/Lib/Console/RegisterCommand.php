<?php

namespace App\Shared\App\Lib\Console;

use Symfony\Component\Console\Command\Command;

class RegisterCommand
{
    /**
     * @var string[]|BaseCommand[]
     */
    private array $commands = [];

    /**
     * @param string|BaseCommand $commandClass
     * @return void
     */
    public  function add(string $commandClass): void
    {
        $this->commands[] = $commandClass;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}