<?php

// load dependencies
require_once __DIR__ . "/../vendor/autoload.php";
$providers = (require __DIR__ . '/../src/Shared/App/config.php')['providers'];

// env
$envName = isset($envName) ? $envName : null; // override while test run
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../',$envName);
$dotenv->load();


// register modules
const APP = new \App\Shared\App\Lib\App();
foreach ($providers as $provider) {
    APP->registerServiceProvider(new $provider(APP));
}

// boot modules
APP->boot();

unset($providers);