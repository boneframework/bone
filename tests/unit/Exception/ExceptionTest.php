<?php


namespace BoneTest\Exception;

use Bone\Exception;
use Closure;
use Codeception\Test\Unit;
use function ob_get_clean;
use function ob_start;

class ExceptionTest extends Unit
{
    public function testShutdowError()
    {
        putenv('TEST_ERROR=true');
        $handler = Exception::getShutdownHandler();
        $this->assertInstanceOf(Closure::class, $handler);
        ob_start();
        $handler();
        $output = ob_get_clean();
        $this->assertIsString($output);
        $this->assertStringContainsString('<body id=\'error\'>', $output);
        putenv('TEST_ERROR=false');
    }

    public function testNoShutdownError()
    {
        $env = getenv('APPLICATION_ENV');
        putenv('TEST_ERROR=true');
        putenv('APPLICATION_ENV=production');
        $handler = Exception::getShutdownHandler();
        $this->assertInstanceOf(Closure::class, $handler);
        ob_start();
        $handler();
        $output = ob_get_clean();
        $this->assertStringContainsString('There was an error', $output);
        putenv('APPLICATION_ENV=' . $env);
        putenv('TEST_ERROR=false');
    }

    public function testProductionError()
    {
        $handler = Exception::getShutdownHandler();
        $this->assertInstanceOf(Closure::class, $handler);
        ob_start();
        $handler();
        $output = ob_get_clean();
        $this->assertEmpty($output);
    }
}


