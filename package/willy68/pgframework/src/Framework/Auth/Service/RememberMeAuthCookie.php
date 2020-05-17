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

    /**
     *
     * @var UserProvider
     */
    private $userProvider;

    /**
     * Cookie options
     *
     * @var array
     */
    private $options = [
        'name' => 'auth_login',
        'expires' => 3600 * 24 * 3,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httpOnly' => false
    ];

    public function __construct(UserProvider $userProvider, array $options = [] )
    {
        $this->userProvider = $userProvider;
        if (!empty($options)) {
            array_merge($this->options, $options);
        }
    }

    public function onLogin(
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

        $cookie = SetCookie::create($this->options['name'])
            ->withValue($value)
            ->withExpires(time() + $this->options['expires'])
            ->withPath('/')
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
        return FigResponseCookies::set($response, $cookie);

    }

    public function autoLogin(ServerRequestInterface $request, string $secret): ?User
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
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

    public function onLogout(ServerRequestInterface $request,  ResponseInterface $response): ResponseInterface
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
        if ($cookie->getValue()) {
            $cookie = SetCookie::create($this->options['name'])
            ->withValue('')
            ->withExpires(time() - 3600)
            ->withPath('/')
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;
    }

    public function resume(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
        if ($cookie->getValue()) {
            $setCookie = SetCookie::create($this->options['name'])
                ->withValue($cookie->getValue())
                ->withExpires(time() + $this->options['expires'])
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
