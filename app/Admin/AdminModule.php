<?php

namespace App\Admin;

use Framework\Module;
use Framework\Router;
use App\Admin\Controller\AdminController;
use Framework\Renderer\RendererInterface;
use Framework\Router\RouteGroup;

class AdminModule extends Module
{
    public function __construct(Router $router, RendererInterface $renderer)
    {
        $renderer->addPath('admin', __DIR__ . '/views');

        $router->group('/admin', function (RouteGroup $route) {
            $route->get('/', AdminController::class . '::index', 'admin.index');
        });
    }
}
