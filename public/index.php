<?php

use App\Admin\AdminModule;
use App\Api\ApiClientModule;
use App\Api\ApiModule;
use App\Demo\DemoModule;
use Middlewares\Whoops;
use GuzzleHttp\Psr7\ServerRequest;
use Framework\Middleware\{
    ApiHeadMiddleware,
    ApiOptionsMiddleware,
    MethodMiddleware,
    RouterMiddleware,
    DispatcherMiddleware,
    MethodNotAllowedMiddleware,
    PageNotFoundMiddleware,
    TrailingSlashMiddleware
};

use function Http\Response\send;

$basePath = dirname(__DIR__);

putenv("ENV=dev");

putenv('JWT_SECRET=MasuperPhraseSecrete');

require $basePath . '/vendor/autoload.php';

$app = (new Framework\App(
    [
        $basePath . '/config/config.php',
        $basePath . '/config/database.php'
    ]
))
    ->addModule(DemoModule::class)
    ->addModule(AdminModule::class)
    ->addModule(ApiModule::class)
    ->addModule(ApiClientModule::class);

$container = $app->getContainer();

$app->pipe(Whoops::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(MethodMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(ApiHeadMiddleware::class)
    ->pipe(ApiOptionsMiddleware::class)
    ->pipe(MethodNotAllowedMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(PageNotFoundMiddleware::class);

if (php_sapi_name() !== 'cli') {
    $response = $app->run(ServerRequest::fromGlobals());
    send($response);
}
