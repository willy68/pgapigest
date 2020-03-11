<?php

namespace App\Demo;

use App\Demo\Controller\DemoController;
use Framework\Middleware\ActiveRecordMiddleware;
use Framework\Module;
use Framework\Router;
use Psr\Container\ContainerInterface;
use Framework\Renderer\RendererInterface;

class DemoModule extends Module
{

    public function __construct(ContainerInterface $c)
    {
        /** @var RendererInterface $renderer */
        $renderer = $c->get(RendererInterface::class);
        $renderer->addPath('demo', __DIR__ . '/views');
        
        /** @var Router $router */
        $router = $c->get(Router::class);
        $router->get('/', DemoController::class . '::index', 'demo.index')
            ->middleware(ActiveRecordMiddleware::class);
    }
}
