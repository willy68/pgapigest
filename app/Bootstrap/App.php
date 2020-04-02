<?php

use App\Api\ApiModule;
use App\Demo\DemoModule;
use App\Admin\AdminModule;
use App\Api\ApiClientModule;
use Framework\Middleware\MethodMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\ApiHeadMiddleware;
use Framework\Middleware\ApiOptionsMiddleware;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\PageNotFoundMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use Framework\Middleware\MethodNotAllowedMiddleware;

return [
    /* Application modules. Place your own on the list */
    'modules' => [
        DemoModule::class,
        AdminModule::class,
        ApiModule::class,
        ApiClientModule::class
    ],

    /* Base middlewares PageNotFound must be the last.
       Other middlewares must be put on Router, RouteGroup or Route */
    'middlewares' => [
        TrailingSlashMiddleware::class,
        MethodMiddleware::class,
        RouterMiddleware::class,
        ApiHeadMiddleware::class,
        ApiOptionsMiddleware::class,
        MethodNotAllowedMiddleware::class,
        DispatcherMiddleware::class,
        PageNotFoundMiddleware::class
    ],

    /* DI Base configuration. Place your own on the list */
    'config' => [
        dirname(dirname(__DIR__)) . '/config/config.php',
        dirname(dirname(__DIR__)) . '/config/router.php',
        dirname(dirname(__DIR__)) . '/config/database.php',
        dirname(dirname(__DIR__)) . '/config/twig.php'
    ]
];
