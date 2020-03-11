<?php

namespace App\Admin;

use Framework\Module;
use Framework\Router;
use Psr\Container\ContainerInterface;
use App\Admin\Controller\AdminController;
use Framework\Renderer\RendererInterface;
use Framework\Router\RouteGroup;

class AdminModule extends Module
{
    public function __construct(ContainerInterface $c)
    {
        /** @var RendererInterface $renderer */
        $renderer = $c->get(RendererInterface::class);
        $renderer->addPath('admin', __DIR__ . '/views');
        
        /** @var Router $router */
        $router = $c->get(Router::class);
        $router->group('/admin', function (RouteGroup $route) {
            $route->get('/', AdminController::class . '::index', 'admin.index');
        });
    }
}
