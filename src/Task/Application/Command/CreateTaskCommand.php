<?php

namespace App\Task\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use App\Shared\HttpServer\Lib\Client\HttpClient;
use Swoole\Runtime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Swoole\Coroutine\run;

class CreateTaskCommand extends BaseCommand
{
    protected function getSignature(): string
    {
        return 'task:create';
    }

    protected function configure()
    {
        $this->setDescription('create task')
            ->addArgument('title', InputArgument::REQUIRED, 'title task')
            ->addArgument('description', InputArgument::REQUIRED, 'description task');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \Co\run(function () use ($input): void {
            \go(function () use ($input): void {
                $client = new HttpClient(env('HTTP_HOST'), env('HTTP_PORT'));
                $result = $client->post('/task', [
                    'title' => $input->getArgument('title'),
                    'description' => $input->getArgument('description')
                ]);

                dump(json_decode($result, true));
            });
        });
        return Command::SUCCESS;
    }

}