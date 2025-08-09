<?php

namespace App\Shared\App\Lib;

use App\Shared\App\Lib\Config\ConfigManager;
use App\Shared\App\Lib\Console\RegisterCommand;
use App\Shared\App\Lib\Event\NotificationCenter;
use App\Shared\App\Lib\Helper\RegisterHelperFunction;
use App\Shared\App\Lib\ServiceProvider\BaseServiceProvider;
use App\Shared\App\Lib\ServiceProvider\ServiceProviderManager;
use App\Shared\HttpServer\Lib\Router;

class App
{
    private ServiceProviderManager $serviceProviderManager;

    private ConfigManager $configManager;
    private Router $router;

    private RegisterCommand $registerCommand;

    private RegisterHelperFunction $registerHelperFunction;

    private NotificationCenter $notificationCenter;

    /** @var array<string, object> */
    private array $registry = [];
    private array $lazyRegistry = [];

    public function __construct(private string $rootPath)
    {
        $this->configManager = new ConfigManager(__DIR__ . '/../config.php');
        $this->registerCommand = new registerCommand();
        $this->serviceProviderManager = new ServiceProviderManager();
        $this->registerHelperFunction = new RegisterHelperFunction();
        $this->router = new Router($this);
        $this->notificationCenter = new NotificationCenter();
    }

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function singleton(string $key, object $value): void
    {
        $this->registry[$key] = $value;
    }

    public function lazyBind(string $key, callable $value): void
    {
        $this->lazyRegistry[$key] = $value;
    }

    public function lazyGet(string $key): object
    {
        if (!isset($this->registry[$key])) {
            $this->registry[$key] = $this->lazyRegistry[$key]();
        }

        return $this->registry[$key];
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

    public function registerHelperFunction(): RegisterHelperFunction
    {
        return $this->registerHelperFunction;
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

    /**
     * application life cycle
     * @return void
     */
    public function boot(): void
    {
        $this->serviceProviderManager->runRegister();
        $this->serviceProviderManager->runBoot();
    }
}