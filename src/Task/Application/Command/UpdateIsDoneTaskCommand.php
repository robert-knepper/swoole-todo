<?php

namespace App\Task\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use App\Shared\HttpServer\Lib\Client\Exception\ServerNotfoundException;
use App\Shared\HttpServer\Lib\Client\HttpClient;
use App\Shared\HttpServer\Lib\Response\HttpDefaultResponse;
use App\Shared\HttpServer\Lib\Response\HttpStatus;
use App\Task\Application\Command\Trait\TaskTableConsole;
use Swoole\Runtime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Swoole\Coroutine\run;

class UpdateIsDoneTaskCommand extends BaseCommand
{
    use TaskTableConsole;

    protected function getSignature(): string
    {
        return 'task:update-is-done';
    }

    protected function configure()
    {
        $this->setDescription('update is done')
            ->addArgument('id', InputArgument::REQUIRED, 'id')
            ->addArgument('isDone', InputArgument::REQUIRED, 'isDone task [2 = false, 1 = true]');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        \Co\run(function () use ($input, $output): void {
            try {
                $client = new HttpClient(env('HTTP_HOST'), env('HTTP_PORT'), true);

                $result = $client->post('/task/update-is-done', [
                    'id' => $input->getArgument('id'),
                    'isDone' => (int)$input->getArgument('isDone')
                ]);

                if ($result['code'] === HttpStatus::OK)
                    $output->writeln('updated');
                else
                    $output->writeln('error ' . $result['message']);

            } catch (ServerNotfoundException $exception) {
                $output->writeln($exception->getMessage());
            }
        });
        return Command::SUCCESS;
    }

}