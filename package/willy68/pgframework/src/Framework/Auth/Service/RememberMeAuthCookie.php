<?php

namespace Framework\Auth\Service;

use Framework\Auth;
use Framework\Auth\User;
use Dflydev\FigCookies\SetCookie;
use App\Auth\ActiveRecordUserProvider;
use Psr\Http\Message\ResponseInterface;
use Framework\Auth\Provider\UserProvider;
use Dflydev\FigCookies\FigResponseCookies;
use Psr\Http\Message\ServerRequestInterface;

class RememberMeAuthCookie
{

    const COOKIE_NAME = 'auth_login';

    private $userProvider;

    private $cookie;

    public function __construct(ActiveRecordUserProvider $userProvider )
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

        $this->cookie = SetCookie::create(SELF::COOKIE_NAME)
                    ->withValue($value)
                    ->withExpires(time() + 3600 * 24 * 3)
                    ->withPath('/')
                    ->withDomain(null)
                    ->withSecure(false)
                    ->withHttpOnly(false);
        return FigResponseCookies::set($response, $this->cookie);

    }

    public function autoLogin(ServerRequestInterface $request, string $secret): ?User
    {
        $cookies = $request->getCookieParams();
        if (!empty($cookie = $cookies[self::COOKIE_NAME])) {
            list($username, $password) = explode(':', $cookie);
            $username = base64_decode($username);
            $user = $this->userProvider->getUser('username', $username);
            if (AuthSecurityToken::validateSecurityToken(
                $cookie,
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

    /**
     * Get the value of cookie
     */ 
    public function getCookie()
    {
        return $this->cookie;
    }
}
