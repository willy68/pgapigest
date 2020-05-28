<?php

use Framework\{
    Auth,
    Auth\AuthSession,
    Auth\User
};
use App\Auth\{
    ActiveRecordUserProvider,
    Twig\AuthTwigExtension,
    Middleware\ForbidenMiddleware
};
use Framework\Auth\Provider\UserProvider;
use Framework\Auth\RememberMe\RememberMe;
use Framework\Auth\RememberMe\RememberMeInterface;

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
    Auth::class => \DI\get(AuthSession::class),
    User::class => factory(function (Auth $auth) {
        return $auth->getUser();
    })->parameter('auth', get(Auth::class)),
    RememberMeInterface::class => \DI\get(RememberMe::class),
    UserProvider::class => \DI\get(ActiveRecordUserProvider::class),
    ForbidenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
