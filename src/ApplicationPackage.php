<?php

declare(strict_types=1);

namespace Bone;

use Barnacle\RegistrationInterface;
use Bone\Console\CommandRegistrationInterface;
use Bone\Console\ConsoleApplication;
use Bone\Console\ConsolePackage;
use Bone\Contracts\Container\ContainerInterface;
use Bone\Contracts\Container\EntityRegistrationInterface;
use Bone\Contracts\Container\RegistrationInterface as NewRegistrationInterface;
use Bone\Db\DbPackage;
use Bone\Firewall\FirewallPackage;
use Bone\Http\GlobalMiddlewareRegistrationInterface;
use Bone\Http\Middleware\Stack;
use Bone\Http\MiddlewareRegistrationInterface;
use Bone\I18n\I18nPackage;
use Bone\I18n\I18nRegistrationInterface;
use Bone\Log\LogPackage;
use Bone\Router\Router;
use Bone\Router\RouterConfigInterface;
use Bone\Router\RouterPackage;
use Bone\View\ViewEngine;
use Bone\I18n\Service\TranslatorFactory;
use Bone\View\ViewPackage;
use Bone\View\ViewRegistrationInterface;
use League\Plates\Template\Folder;
use League\Plates\Template\Folders;
use Laminas\I18n\Translator\Translator;
use Psr\Http\Server\MiddlewareInterface;
use function reset;

class ApplicationPackage implements NewRegistrationInterface
{
    private array $config;
    private Router $router;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function addToContainer(ContainerInterface $c): void
    {
        $this->setConfigArray($c);
        $this->setupLogs($c);
        $this->setupPdoConnection($c);
        $this->setupRouter($c);
        $this->initMiddlewareStack($c);
        $this->setupViewEngine($c);
        $this->initConsoleApp($c);
        $this->setupTranslator($c);
        $this->setupPackages($c);
        $this->setupVendorViewOverrides($c);
        $this->setupRouteFirewall($c);
        $this->setupMiddlewareStack($c);
        $this->setupConsoleApp($c);
    }

    private function setConfigArray(ContainerInterface $c): void
    {
        foreach ($this->config as $key => $value) {
            $c[$key] = $value;
        }
    }

    private function setupViewEngine(ContainerInterface $c): void
    {
        $package = new ViewPackage();
        $package->addToContainer($c);
        $this->addMiddlewaresToContainer($package, $c);
        $this->addMiddlewaresToStack($package, $c);
    }

    private function setupRouter(ContainerInterface $c): void
    {
        $package = new RouterPackage();
        $package->addToContainer($c);
        $this->router = $c->get(Router::class);
    }

    private function setupPackages(ContainerInterface $c): void
    {
        // set up the modules and vendor package modules
        $c['consoleCommands'] = $c->has('consoleCommands') ? $c->get('consoleCommands') : [];
        $packages = $c->get('packages');
        $this->addEntityPathsFromPackages($packages, $c);

        reset($packages);

        foreach ($packages as $packageName) {
            if (class_exists($packageName)) {
                $this->registerPackage($packageName, $c);
            }
        }
    }

    private function registerPackage(string $packageName, ContainerInterface $c): void
    {
        /** @var RegistrationInterface $package */
        $package = new $packageName();
        $package->addToContainer($c);
        $this->registerRoutes($package, $c);
        $this->registerViews($package, $c);
        $this->registerTranslations($package, $c);
        $this->registerMiddleware($package, $c);
        $this->registerConsoleCommands($package, $c);
    }

    private function registerConsoleCommands(RegistrationInterface $package, ContainerInterface $c): void
    {
        $consoleCommands = $c->get('consoleCommands');

        if ($package instanceof CommandRegistrationInterface) {
            $commands = $package->registerConsoleCommands($c);

            foreach ($commands as $command) {
                $consoleCommands[] = $command;
            }
        }

        $c['consoleCommands'] = $consoleCommands;
    }

    private function registerMiddleware(RegistrationInterface $package, ContainerInterface $c): void
    {
        if ($package instanceof MiddlewareRegistrationInterface) {
            $this->addMiddlewaresToContainer($package, $c);
        }

        if ($package instanceof GlobalMiddlewareRegistrationInterface) {
            $this->addMiddlewaresToStack($package, $c);
        }
    }

    private function addMiddlewaresToContainer(MiddlewareRegistrationInterface $package, ContainerInterface $c): void
    {
        $middlewares = $package->getMiddleware($c);

        foreach ($middlewares as $middleware) {
            $className = get_class($middleware);
            $c[$className] = $middleware;
        }
    }

    private function addMiddlewaresToStack(GlobalMiddlewareRegistrationInterface $package, ContainerInterface $c): void
    {
        /** @var Stack $stack */
        $stack = $c->get(Stack::class);
        $middlewares = $package->getGlobalMiddleware($c);

        foreach ($middlewares as $middleware) {
            $stack->addMiddleWare($c->get($middleware));
        }
    }

    private function registerRoutes(RegistrationInterface $package, ContainerInterface $c): void
    {
        if ($package instanceof RouterConfigInterface) {
            $package->addRoutes($c, $this->router);
        }
    }

    private function registerViews(RegistrationInterface $package, ContainerInterface $c): void
    {
        if ($package instanceof ViewRegistrationInterface) {
            $views = $package->addViews();
            $extensions = $package->addViewExtensions($c);
            /** @var ViewEngine $engine */
            $engine = $c->get(ViewEngine::class);

            foreach ($views as $name => $folder) {
                $engine->addFolder($name, $folder);
            }

            foreach ($extensions as $extension) {
                $engine->loadExtension($extension);
            }
        }
    }

    private function registerTranslations(RegistrationInterface $package, ContainerInterface $c): void
    {
        $i18n = $c->get('i18n');
        /** @var Translator $translator */
        $translator = $c->get(Translator::class);

        if ($package instanceof I18nRegistrationInterface) {
            foreach ($i18n['supported_locales'] as $locale) {
                $factory = new TranslatorFactory();
                $factory->addPackageTranslations($translator, $package, $locale);
            }
        }
    }

    private function initConsoleApp(ContainerInterface $c): void
    {
        $c[ConsoleApplication::class] = new ConsoleApplication();
    }

    private function setupConsoleApp(ContainerInterface $c): void
    {
        $package = new ConsolePackage();
        $package->addToContainer($c);
    }

    private function addEntityPathsFromPackages(array $packages, ContainerInterface $c): void
    {
        foreach ($packages as $packageName) {
            if (class_exists($packageName)) {
                /** @var RegistrationInterface $package */
                $package = new $packageName();

                if ($package instanceof EntityRegistrationInterface) {
                    $paths = $c['entity_paths'];
                    $paths[] = $package->getEntityPath();
                    $c['entity_paths'] = $paths;
                }
            }
        }
    }

    private function setupTranslator(ContainerInterface $c): void
    {
        $package = new I18nPackage();
        $package->addToContainer($c);
        $this->addMiddlewaresToContainer($package, $c);
        $this->addMiddlewaresToStack($package, $c);
    }

    private function setupPdoConnection(ContainerInterface $c): void
    {
        $package = new DbPackage();
        $package->addToContainer($c);
    }

    private function setupRouteFirewall(ContainerInterface $c): void
    {
        $package = new FirewallPackage();
        $package->addToContainer($c);
        $this->addMiddlewaresToContainer($package, $c);
        $this->addMiddlewaresToStack($package, $c);
    }

    private function  setupLogs(ContainerInterface $c): void
    {
        $package = new LogPackage();
        $package->addToContainer($c);
    }

    private function setupVendorViewOverrides(ContainerInterface $c): void
    {
        /** @var ViewEngine $viewEngine */
        $viewEngine = $c->get(ViewEngine::class);
        $views = $c->get('views');
        $registeredViews = $viewEngine->getFolders();

        foreach ($views as $view => $folder) {
            $this->overrideViewFolder($view, $folder, $registeredViews);
        }
    }

    private function overrideViewFolder(string $view, string $folder, Folders $registeredViews): void
    {
        if ($registeredViews->exists($view)) {
            /** @var Folder $currentFolder */
            $currentFolder = $registeredViews->get($view);
            $currentFolder->setPath($folder);
        }
    }

    private function initMiddlewareStack(ContainerInterface $c): void
    {
        $c[Stack::class] = new Stack($this->router);
    }

    private function setupMiddlewareStack(ContainerInterface $c): void
    {
        $stack = $c->get(Stack::class);
        $middlewareStack = $c->has('stack') ? $c->get('stack') : [];

        foreach ($middlewareStack as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $stack->addMiddleWare($middleware);
            } elseif ($c->has($middleware)) {
                $stack->addMiddleWare($c->get($middleware));
            } else {
                $stack->addMiddleWare(new $middleware());
            }
        }
    }
}
