<?php

namespace App\Shared\HttpServer\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartServerCommand extends BaseCommand
{
    protected function getSignature(): string
    {
        return 'http-server:start-server';
    }

    protected function configure()
    {
        $this->setDescription('start http server on ' . env('HTTP_HOST') . ':' . env('HTTP_PORT'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $httpServer = new \App\Shared\HttpServer\Lib\HttpServer(
            env('HTTP_HOST'),
            env('HTTP_PORT')
        );
        $httpServer->start();

        return Command::SUCCESS;
    }

}