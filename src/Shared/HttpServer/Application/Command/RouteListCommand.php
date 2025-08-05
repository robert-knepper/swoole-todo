<?php

namespace App\Shared\HttpServer\Application\Command;

use App\Shared\App\Lib\Console\BaseCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends BaseCommand
{
    protected function getSignature(): string
    {
        return 'http-server:route-list';
    }

    protected function configure()
    {
        $this->setDescription('show all registered routes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routes = APP->getRouter()->getRoutes();
        $table = new Table($output);
        $table->setHeaders(['Method', 'URI', 'Class', 'Method']);

        foreach ($routes as $httpMethod => $endpoints) {
            foreach ($endpoints as $uri => $handler) {
                $table->addRow([
                    $httpMethod,
                    $uri,
                    $handler[0],
                    $handler[1],
                ]);
            }
        }

        $table->render();
        return Command::SUCCESS;
    }

}