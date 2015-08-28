<?php
// This is global bootstrap for autoloading

include __DIR__.'/../vendor/autoload.php'; // composer autoload

define('APPLICATION_PATH', realpath(__DIR__ . '/../'));

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [__DIR__.'/../src']
]);

