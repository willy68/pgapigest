<?php

namespace Framework\Auth\Service;

use Framework\Auth\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RememberMeInterface
{
    public function login(ResponseInterface $response, string $secret): ResponseInterface;

    public function autoLogin(ServerRequestInterface $request): ?User;

    public function logout(ResponseInterface $response): ResponseInterface;
}
