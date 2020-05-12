<?php

namespace Framework\Auth\Service;

use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigResponseCookies;
use Framework\Auth;
use Framework\Auth\Provider\UserProvider;
use Framework\Auth\User;
use Psr\Http\Message\ServerRequestInterface;

class RememberMeAuthCookie
{

    const COOKIE_NAME = 'auth.login';

    private $auth;

    private $userProvider;

    private $cookie;

    public function __construct(Auth $auth, UserProvider $userProvider )
    {
        $this->auth = $auth;
        $this->userProvider = $userProvider;
    }

    public function login(ResponseInterface $response, string $secret): ResponseInterface
    {
        $username = base64_encode($this->auth->getUser()->getUsername());

        $value = base64_encode($username) . ':' . sha1($secret);

        $this->cookie = SetCookie::create(SELF::COOKIE_NAME)
                    ->withValue($value)
                    ->withExpires(time() + 3600 * 24 * 3)
                    ->withPath('/')
                    ->withDomain(null)
                    ->withSecure(false)
                    ->withHttpOnly(false);
        return FigResponseCookies::set($response, $this->cookie);

    }

    public function autoLogin(ServerRequestInterface $request): ?User
    {
        if ($user = $this->auth->getUser()) {
            return $user;
        }
        $cookies = $request->getCookieParams();
        if (!empty($cookie = $cookies[self::COOKIE_NAME])) {
            list($username, $secret) = explode(':', $cookie);
            $username = base64_decode($username);
            return $this->userProvider->getUser('username', $username);
        }
        return null;
    }

    public function logout(ResponseInterface $response): ResponseInterface
    {
        SetCookie::create(SELF::COOKIE_NAME)
            ->withValue('')
            ->withExpires(time() - 3600)
            ->withPath('/')
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
        return FigResponseCookies::set($response, $this->cookie);
    }

    /**
     * Get the value of cookie
     */ 
    public function getCookie()
    {
        return $this->cookie;
    }
}
