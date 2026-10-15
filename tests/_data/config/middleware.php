<?php

use Bone\Firewall\RouteFirewall;
use Tests\TestPackage\Http\Middleware\TestMiddleware;

return [
    'stack' => [
        RouteFirewall::class,
        'Tests\TestPackage\Http\Middleware\TestMiddleware',
        new TestMiddleware(),
    ],
];
