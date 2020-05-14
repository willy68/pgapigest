<?php

use Framework\{
    Auth,
    Auth\User
};
use App\Auth\{
    ActiveRecordUserProvider,
    DatabaseAuth,
    Twig\AuthTwigExtension,
    Middleware\ForbidenMiddleware
};
use Framework\Auth\Provider\UserProvider;
use Framework\Auth\Service\RememberMeAuthCookie;
use Framework\Auth\Service\RememberMeInterface;

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
    User::class => factory(function (Auth $auth) {
        return $auth->getUser();
    })->parameter('auth', get(Auth::class)),
    UserProvider::class => \DI\get(ActiveRecordUserProvider::class),
    RememberMeInterface::class => \DI\get(RememberMeAuthCookie::class),
    Auth::class => \DI\get(DatabaseAuth::class),
    ForbidenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
