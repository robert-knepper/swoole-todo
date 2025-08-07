<?php

namespace App\Task\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use App\Shared\HttpServer\Lib\Client\Exception\ServerNotfoundException;
use App\Shared\HttpServer\Lib\Client\HttpClient;
use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Task\Application\Command\Trait\TaskTableConsole;
use Swoole\Runtime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Swoole\Coroutine\run;

class GetTaskCommand extends BaseCommand
{
    use TaskTableConsole;

    protected function getSignature(): string
    {
        return 'task:get';
    }

    protected function configure()
    {
        $this->setDescription('get task by id')
            ->addArgument('id', InputArgument::REQUIRED, 'task id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        \Swoole\Coroutine\run(function () use ($input, $output): void {
            try {
                $client = new HttpClient(env('HTTP_HOST'), env('HTTP_PORT'), true);
                $result = $client->get('/task?id=' . $input->getArgument('id'));
                if ($result['code'] === HttpStatus::OK)
                    $this->renderTaskTable($output, $result);
                else
                    $output->writeln($result['message']);

            } catch (ServerNotfoundException $exception) {
                $output->writeln($exception->getMessage());
            }
        });
        return Command::SUCCESS;
    }

}