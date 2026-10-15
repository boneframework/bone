<?php declare(strict_types=1);

namespace Tests;

use Barnacle\Container;
use Bone\ApplicationPackage;
use Bone\Router\Router;
use Bone\Server\Environment;
use Bone\Server\SiteConfig;
use Codeception\Test\Unit;
use Tests\Http\RequestHandler\I18nTestHandler;

class ApplicationPackageTest extends Unit
{
    private I18nTestHandler $middleware;

    public function testPackage(): void
    {
        $container = new Container();
        $env = new Environment($_SERVER);
        $config = $env->fetchConfig('tests/_data/config', getenv('APPLICATION_ENV'));
        $container[SiteConfig::class] = new SiteConfig($config, $env);
        $router = $container[Router::class] = new Router();
        $package = new ApplicationPackage($config, $router);
        $package->addToContainer($container);
        $pdo = $container->get(\PDO::class);
        $this->assertInstanceOf(\PDO::class, $pdo);
    }

    private function runPrivateMethod($object, string $method, ...$args)
    {
        $mirror = new \ReflectionClass($object);
        $method = $mirror->getMethod($method);
        $method->setAccessible(true);

        return $method->invoke($object, $args);
    }
}
