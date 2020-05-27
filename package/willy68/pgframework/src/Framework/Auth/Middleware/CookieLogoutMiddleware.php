<?php

namespace Framework\Auth\Middleware;

use Framework\Auth\Service\AuthSessionCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CookieLogoutMiddleware implements MiddlewareInterface
{
    /**
     *
     * @var AuthSessionCookie
     */
    private $auth;

    public function __construct(AuthSessionCookie $auth)
    {
        $this->auth = $auth;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        $user = $this->auth->getUser();
        if (!$user) {
            return $this->auth->onLogout($request, $response);
        }
        return $response;
    }
}
