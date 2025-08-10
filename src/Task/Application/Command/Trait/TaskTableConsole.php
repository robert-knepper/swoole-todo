<?php

namespace App\Task\Application\Command\Trait;

use App\Task\Domain\Task;
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
            $task['isDone'] = $task['isDone'] ? '✅' : '❌';
            $task['createdAt'] = date('Y-m-d H:i:s', $task['createdAt']);
            return $task;
        }, $tasks);

        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows)
            ->render();
    }
}