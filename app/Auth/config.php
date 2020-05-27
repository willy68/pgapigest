<?php

use Framework\{
    Auth,
    Auth\User
};
use App\Auth\{
    ActiveRecordUserProvider,
    Twig\AuthTwigExtension,
    Middleware\ForbidenMiddleware
};
use Framework\Auth\Provider\UserProvider;
use Framework\Auth\Service\AuthSessionCookie;

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
    Auth::class => \DI\get(AuthSessionCookie::class),
    User::class => factory(function (Auth $auth) {
        return $auth->getUser();
    })->parameter('auth', get(Auth::class)),
    UserProvider::class => \DI\get(ActiveRecordUserProvider::class),
    ForbidenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
