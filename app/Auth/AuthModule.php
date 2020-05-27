<?php

namespace App\Auth;

use Framework\Module;
use Framework\Router;
use App\Auth\Actions\LoginAction;
use App\Auth\Actions\LogoutAction;
use Psr\Container\ContainerInterface;
use App\Auth\Actions\LoginAttemptAction;
use Framework\Renderer\RendererInterface;
use Framework\Auth\Middleware\CookieLogoutMiddleware;

class AuthModule extends Module
{
    public const DEFINITIONS = __DIR__ . '/config.php';

    public const MIGRATIONS = __DIR__ . '/db/migrations';

    public const SEEDS = __DIR__ . '/db/seeds';

    public function __construct(ContainerInterface $container)
    {
        $container->get(RendererInterface::class)->addPath('auth', __DIR__ . '/views');
        $router = $container->get(Router::class);
        $router->get($container->get('auth.login'), LoginAction::class, 'auth.login');
        $router->post($container->get('auth.login'), LoginAttemptAction::class);
        $router->post('/logout', LogoutAction::class, 'auth.logout')
            ->middleware(CookieLogoutMiddleware::class);
    }
}
