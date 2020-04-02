<?php

namespace App\Demo;

use Framework\Module;
use Framework\Router;
use Framework\Renderer\RendererInterface;
use App\Demo\Controller\DemoController;

class DemoModule extends Module
{

    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('demo', __DIR__ . '/views');

        $router->get('/', DemoController::class . '::index', 'demo.index');
    }
}
