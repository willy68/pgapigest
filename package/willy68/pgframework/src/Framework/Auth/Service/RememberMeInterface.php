<?php

namespace Framework\Auth\Service;

use Framework\Auth\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RememberMeInterface
{
    public function login(
        ResponseInterface $response,
        string $username,
        string $password,
        string $secret
    ): ResponseInterface;

    public function autoLogin(ServerRequestInterface $request, string $secret): ?User;

    public function logout(ResponseInterface $response): ResponseInterface;
}
