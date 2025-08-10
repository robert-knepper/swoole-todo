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

    /**
     * When you think this object might never be instantiated during the program’s runtime, and you don’t want unnecessary objects to be created and stored in memory, you can use this method.
     * It allows you to pass a closure so that an object is created from it only when needed.
     * @param string $key
     * @param callable $value
     * @return void
     */
    public function lazyBind(string $key, callable $value): void
    {
        $this->lazyRegistry[$key] = $value;
    }

    /**
     * Use this function when you know it’s the first time you’re going to receive a lazyBind and it needs to be checked — if it doesn’t exist, the object should be created.
     * However, in places where you’re 100% sure the object has already been created, use get method.
     * The goal was to remove an extra if inside get method.
     * @param string $key
     * @return object
     */
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