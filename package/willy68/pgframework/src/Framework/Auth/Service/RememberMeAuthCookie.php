<?php

namespace Framework\Auth\Service;

use Framework\Auth\User;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Framework\Auth\Provider\UserProvider;
use Dflydev\FigCookies\FigResponseCookies;
use Psr\Http\Message\ServerRequestInterface;

class RememberMeAuthCookie implements RememberMeInterface
{

    const COOKIE_NAME = 'auth_login';

    private $userProvider;

    public function __construct(UserProvider $userProvider )
    {
        $this->userProvider = $userProvider;
    }

    public function login(
        ResponseInterface $response,
        string $username,
        string $password,
        string $secret
    ): ResponseInterface
    {
        $value = AuthSecurityToken::generateSecurityToken(
            $username,
            $password,
            $secret);

        $cookie = SetCookie::create(SELF::COOKIE_NAME)
            ->withValue($value)
            ->withExpires(time() + 3600 * 24 * 3)
            ->withPath('/')
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
        return FigResponseCookies::set($response, $cookie);

    }

    public function autoLogin(ServerRequestInterface $request, string $secret): ?User
    {
        $cookie = FigRequestCookies::get($request, self::COOKIE_NAME);
        if ($cookie->getValue()) {
            list($username, $password) = AuthSecurityToken::decodeSecurityToken($cookie->getValue());
            $user = $this->userProvider->getUser('username', $username);
            if ($user && AuthSecurityToken::validateSecurityToken(
                $cookie->getValue(),
                $username,
                $user->getPassword(),
                $secret
                )
                ) {
                return $user;
            }
        }
        return null;
    }

    public function logout(ResponseInterface $response): ResponseInterface
    {
        $cookie = SetCookie::create(SELF::COOKIE_NAME)
            ->withValue('')
            ->withExpires(time() - 3600)
            ->withPath('/')
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
        return FigResponseCookies::set($response, $cookie);
    }

    public function resume(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cookie = FigRequestCookies::get($request, self::COOKIE_NAME);
        if ($cookie) {
            $setCookie = SetCookie::create(self::COOKIE_NAME)
                ->withValue($cookie->getValue())
                ->withExpires(time() + 3600 * 24 * 3)
                ->withPath('/')
                ->withDomain(null)
                ->withSecure(false)
                ->withHttpOnly(false);
                $response = FigResponseCookies::set($response, $setCookie);
        }
        return $response;
    }

    /**
     * Get the value of cookie
     */ 
    public function getCookie()
    {
        return $this->cookie;
    }
}
