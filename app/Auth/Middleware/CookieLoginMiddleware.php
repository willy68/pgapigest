<?php

namespace App\Auth\Middleware;

use App\Auth\DatabaseAuth;
use Framework\Auth\ForbiddenException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CookieLoginMiddleware implements MiddlewareInterface
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
        $user = $this->auth->getUser();
        if ($user) {
            return $handler->handle($request);
        }
        $user = $this->auth->autoLogin($request, 'secret');
        if (!$user) {
            throw new ForbiddenException("Cookie invalid");
        }
        $response = $handler->handle($request);
        return $this->auth->resume($request, $response);
    }

}
