<?php

namespace App\Shared\App\Lib;

use App\Shared\App\Lib\Config\ConfigManager;
use App\Shared\App\Lib\Console\RegisterCommand;
use App\Shared\App\Lib\Event\NotificationCenter;
use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\App\Lib\ServiceProvider\ServiceProviderManager;
use App\Shared\HttpServer\Lib\Router;
use Symfony\Component\Console\Application;

class App
{
    private ServiceProviderManager $serviceProviderManager;

    private ConfigManager $configManager;
    private Router $router;

    private RegisterCommand $registerCommand;

    private NotificationCenter $notificationCenter;

    /** @var array<string, object> */
    private array $registry = [];

    public function __construct()
    {
        $this->configManager = new ConfigManager(__DIR__ . '/../config.php');
        $this->registerCommand = new registerCommand();
        $this->serviceProviderManager = new ServiceProviderManager();
        $this->router = new Router($this);
        $this->notificationCenter = new NotificationCenter();
    }

    public function bind(string $key, object $value): void
    {
        $this->registry[$key] = $value;
    }

    public function get(string $key): object
    {
        return $this->registry[$key];
    }

    public function registerCommand(): RegisterCommand
    {
        return $this->registerCommand;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getNotification(): NotificationCenter
    {
        return $this->notificationCenter;
    }

    public function registerServiceProvider(BaseServiceProvider $provider): void
    {
        $this->serviceProviderManager->addProvider($provider);
    }

    public function getConfigManager(): ConfigManager
    {
        return $this->configManager;
    }

    public function boot(): void
    {
        $this->serviceProviderManager->run();
    }
}