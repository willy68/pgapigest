<?php

use Framework\{
    Auth,
    Auth\User
};
use App\Auth\{
    DatabaseAuth,
    Twig\AuthTwigExtension,
    Middleware\ForbidenMiddleware
};

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
    Auth::class => \DI\get(DatabaseAuth::class),
    ForbidenMiddleware::class => \DI\autowire()->constructorParameter('loginPath', \DI\get('auth.login'))
];
