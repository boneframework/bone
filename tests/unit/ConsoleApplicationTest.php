<?php declare(strict_types=1);

namespace Tests;

use Bone\Console\ConsoleApplication;
use Codeception\Test\Unit;

class ConsoleApplicationTest extends Unit
{

    public function testGetLongVersion()
    {
        $app = new ConsoleApplication();
        $this->assertTrue(is_string($app->getLongVersion()));
    }
}
