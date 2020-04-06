<?php

namespace App\Admin;

use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRenderer;
use Framework\Router;

class AdminModule extends Module
{

    public const DEFINITIONS = __DIR__ . '/config.php';

    public function __construct(
        RendererInterface $renderer,
        AdminTwigExtension $adminTwigExtension,
        Router $router,
        string $prefix
    ) {
        $renderer->addPath('admin', __DIR__ . '/views');
        $router->get($prefix, DashboardAction::class . '::index', 'admin');
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($adminTwigExtension);
        }
    }
}
