<?php

declare(strict_types=1);

namespace Bone;

use Barnacle\Container;
use Bone\Http\Middleware\Stack;
use Bone\Server\Environment;
use Bone\Server\SiteConfig;
use Del\SessionManager;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Application
{
    private Container $container;
    private ?ServerRequestInterface $globalRequest = null;
    private string $configFolder = 'config';
    private string $environment = 'production';

    private function __construct(){}
    private function __clone(){}

    public static function ahoy(): Application
    {
        static $inst = null;

        if ($inst === null) {
            $inst = new Application();
            $inst->container = new Container();
            $inst->initSession();
            $env = getenv('APPLICATION_ENV');

            if ($env) {
                $inst->setEnvironment($env);
            }
        }

        return $inst;
    }

    private function initSession(): void
    {
        $session = SessionManager::getInstance();

        if (isset($_SERVER['SERVER_NAME'])) {
            SessionManager::sessionStart('app');
        }

        $this->container[SessionManager::class] = $session;
    }

    /**
     *  Use this to bootstrap Bone without dispatching any request
     *  i.e. for when using the framework in a CLI application
     */
    public function bootstrap(): Container
    {
        \register_shutdown_function(ErrorHandler::getShutdownHandler());
        $env = new Environment($_SERVER);
        $config = $env->fetchConfig($this->configFolder, $this->environment);
        $config[Environment::class] = $env;
        $config[SiteConfig::class] = new SiteConfig($config, $env);
        $package = new ApplicationPackage($config);
        $package->addToContainer($this->container);

        return $this->container;
    }

    public function setSail(): bool
    {
        // load in the config and set up the dependency injection container
        $this->bootstrap();
        $request = $this->getGlobalRequest();
        /** @var RequestHandlerInterface $stack */
        $stack = $this->container->get(Stack::class);
        $response = $stack->handle($request);

        (new SapiEmitter)->emit($response);

        return true;
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function setConfigFolder(string $configFolder): void
    {
        $this->configFolder = $configFolder;
    }

    public function setEnvironment(string $environment): void
    {
        $this->environment = $environment;
    }

    public function getGlobalRequest(): ServerRequestInterface
    {
        return $this->globalRequest
            ? $this->globalRequest
            : ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    }
}
