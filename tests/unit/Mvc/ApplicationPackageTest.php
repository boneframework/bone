<?php

use Barnacle\Container;
use Bone\ApplicationPackage;
use Bone\Router\Router;
use Bone\View\ViewEngine;
use Bone\Server\SiteConfig;
use Bone\Server\Environment;
use Bone\I18n\Http\Middleware\I18nHandler;
use Bone\I18n\Service\TranslatorFactory;
use Codeception\Coverage\Subscriber\Local;
use Codeception\Test\Unit;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use Laminas\I18n\Translator\Loader\Gettext;

class ApplicationPackageTest extends Unit
{
    private I18nHandler $middleware;

    public function testPackage(): void
    {
        $container = new Container();
        $env = new Environment($_SERVER);
        $config = $env->fetchConfig('tests/_data/config', getenv('APPLICATION_ENV'));
        $container[SiteConfig::class] = new SiteConfig($config, $env);
        $router = $container[Router::class] = new Router();
        $package = new ApplicationPackage($config, $router);
        $package->addToContainer($container);
        $pdo = $container->get(PDO::class);
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    private function runPrivateMethod($object, string $method, ...$args)
    {
        $mirror = new ReflectionClass($object);
        $method = $mirror->getMethod($method);
        $method->setAccessible(true);

        return $method->invoke($object, $args);
    }
}
