<?php

namespace App\Task\Application\Command\Trait;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

trait TaskTableConsole
{

    function renderTaskTable(OutputInterface $output, array $task): void
    {
        $this->renderTasksTable($output, [$task]);
    }

    /**
     * Render an array of tasks in table format.
     *
     * @param OutputInterface $output
     * @param array $tasks
     * @return void
     */
    function renderTasksTable(OutputInterface $output, array $tasks): void
    {
        if (empty($tasks)) {
            $output->writeln('<comment>task not found</comment>');
            return;
        }

        $headers = array_keys(array_values($tasks)[0]);

        $rows = array_map(function ($task) {
            // cast bool value
            return array_map(function ($value) {
                if (is_bool($value))
                    return $value ? '✅' : '❌';
                return $value;
            }, $task);
        }, $tasks);

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }
}