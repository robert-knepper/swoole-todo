<?php

namespace App\Task\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use App\Shared\HttpServer\Lib\Client\Exception\ServerNotfoundException;
use App\Shared\HttpServer\Lib\Client\HttpClient;
use Swoole\Runtime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Swoole\Coroutine\run;

class DeleteTaskCommand extends BaseCommand
{
    protected function getSignature(): string
    {
        return 'task:delete';
    }

    protected function configure()
    {
        $this->setDescription('delete task by id')
            ->addArgument('id', InputArgument::REQUIRED, 'task id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \Co\run(function () use ($input, $output): void {
            try {
                $client = new HttpClient(env('HTTP_HOST'), env('HTTP_PORT'));
                $client->post('/task/delete', [
                    'id' => $input->getArgument('id')
                ]);
                $output->writeln('deleted');
            } catch (ServerNotfoundException $exception) {
                $output->writeln($exception->getMessage());
            }
        });
        return Command::SUCCESS;
    }

}