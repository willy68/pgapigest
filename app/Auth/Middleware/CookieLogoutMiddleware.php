<?php

namespace App\Auth\Middleware;

use App\Auth\DatabaseAuth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CookieLogoutMiddleware implements MiddlewareInterface
{
    /**
     *
     * @var DatabaseAuth
     */
    private $auth;

    public function __construct(DatabaseAuth $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $user = $this->auth->getUser();
        if (!$user) {
            return $this->auth->rememberMeLogout($request, $response);
        }
        return $response;
    }
}
