<?php

namespace App\Task\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use App\Shared\HttpServer\Lib\Client\Exception\ServerNotfoundException;
use App\Shared\HttpServer\Lib\Client\HttpClient;
use App\Shared\HttpServer\Lib\Response\DefaultResponseDTO;
use App\Task\Application\Command\Trait\TaskTableConsole;
use Swoole\Runtime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Swoole\Coroutine\run;

class GetAllTaskCommand extends BaseCommand
{
    use TaskTableConsole;

    protected function getSignature(): string
    {
        return 'task:get-all';
    }

    protected function configure()
    {
        $this->setDescription('show all created tasks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \Co\run(function () use ($input, $output): void {
            try {
                $client = new HttpClient(env('HTTP_HOST'), env('HTTP_PORT'), true);
                $result = $client->get('/task/all');
                if (!$result) {
                    $output->writeln('server not found');
                    return;
                }
                $result = new DefaultResponseDTO(...$result);
                if (count($result->getData()) === 0) {
                    $output->writeln('tasks is empty');
                    return;
                }
                $this->renderTasksTable($output, $result->getData());
            } catch (ServerNotfoundException $exception) {
                $output->writeln($exception->getMessage());
            }
        });
        return Command::SUCCESS;
    }

}