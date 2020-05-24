<?php

namespace App\Admin;

use Framework\Module;
use Framework\Router;
use App\Admin\DashboardAction;
use Framework\Auth\Middleware\CookieLoginMiddleware;
use Grafikart\Csrf\CsrfMiddleware;
use App\Blog\Actions\PostCrudAction;
use Framework\Renderer\TwigRenderer;
use Framework\Auth\LoggedInMiddleware;
use App\Blog\Actions\CategoryCrudAction;
use Framework\Renderer\RendererInterface;
use App\Auth\Middleware\ForbidenMiddleware;
use Framework\Middleware\InvalidCsrfMiddleware;

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
        $router->crud("$prefix/posts", PostCrudAction::class, 'blog.admin')
            ->middleware(ForbidenMiddleware::class)
            ->middleware(CookieLoginMiddleware::class)
            ->middleware(LoggedInMiddleware::class)
            ->middleware(InvalidCsrfMiddleware::class)
            ->middleware(CsrfMiddleware::class);
        $router->crud("$prefix/categories", CategoryCrudAction::class, 'blog.admin.category')
            ->middleware(ForbidenMiddleware::class)
            ->middleware(CookieLoginMiddleware::class)
            ->middleware(LoggedInMiddleware::class)
            ->middleware(InvalidCsrfMiddleware::class)
            ->middleware(CsrfMiddleware::class);
        if ($renderer instanceof TwigRenderer) {
            $renderer->getTwig()->addExtension($adminTwigExtension);
        }
    }
}
