<?php

// load dependencies
require_once __DIR__ . "/../vendor/autoload.php";
$providers = (require __DIR__ . '/../src/Shared/App/config.php')['providers'];

// register modules
const APP = new \App\Shared\App\Lib\App(__DIR__ . '/../');
foreach ($providers as $provider) {
    APP->registerServiceProvider(new $provider(APP));
}

// boot modules
Swoole\Runtime::enableCoroutine(true);
Co\run(function () {
    APP->boot();
});

unset($providers, $envName);
