<?php

use Framework\{
    Auth,
    Auth\User,
    Auth\RememberMe\RememberMeInterface
};
use App\Auth\{
    ActiveRecordUserProvider,
    Twig\AuthTwigExtension,
    Middleware\ForbidenMiddleware
};
use Framework\Auth\Provider\UserProvider;
use Framework\Auth\RememberMe\AuthCookieSession;

use function DI\{
    add,
    get,
    factory
};

return [
    'auth.login' => '/login',
    'twig.extensions' => add([
        get(AuthTwigExtension::class)
    ]),
    Auth::class => \DI\get(AuthCookieSession::class),
    User::class => factory(function (Auth $auth) {
        return $auth->getUser();
    })->parameter('auth', get(Auth::class)),
    UserProvider::class => \DI\get(ActiveRecordUserProvider::class),
    RememberMeInterface::class => \DI\get(AuthCookieSession::class),
    ForbidenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
