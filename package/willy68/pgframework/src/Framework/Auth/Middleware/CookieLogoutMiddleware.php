<?php

namespace Framework\Auth\Middleware;

use Framework\Auth\RememberMe\RememberMeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CookieLogoutMiddleware implements MiddlewareInterface
{
    /**
     *
     * @var RememberMeInterface
     */
    private $auth;

    public function __construct(RememberMeInterface $auth)
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
