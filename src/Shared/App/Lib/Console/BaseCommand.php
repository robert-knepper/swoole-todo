<?php

namespace App\Shared\App\Lib\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    protected abstract function getSignature() : string;
    public function __construct()
    {
        parent::__construct($this->getSignature());
    }
}