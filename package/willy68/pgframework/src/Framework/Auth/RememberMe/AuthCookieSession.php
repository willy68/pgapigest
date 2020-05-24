<?php

namespace Framework\Auth\RememberMe;

use Exception;
use Framework\Auth;
use Framework\Auth\User;
use Dflydev\FigCookies\SetCookie;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Framework\Auth\Provider\UserProvider;
use Dflydev\FigCookies\FigResponseCookies;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Auth\Service\AuthSecurityToken;

class AuthCookieSession implements RememberMeInterface
{

    /**
     *
     * @var SessionInterface
     */
    private $session;

    /**
     *
     * @var UserProvider
     */
    private $userProvider;

    /**
     *
     * @var User
     */
    private $user;

    /**
     * Cookie options
     *
     * @var array
     */
    private $options = [
        'name' => 'auth_login',
        'field' => 'username',
        'expires' => 3600 * 24 * 3,
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'httpOnly' => false
    ];

    public function __construct(SessionInterface $session, UserProvider $userProvider, array $options = [] )
    {
        $this->session = $session;
        $this->userProvider = $userProvider;
        if (!empty($options)) {
            array_merge($this->options, $options);
        }
    }

    /**
     *
     * @param string $username
     * @param string $password
     * @return User|null
     */
    public function login(string $username, string $password): ?User
    {
        if (empty($username) || empty($password)) {
            return null;
        }

        /** @var User $user */
        $user = $this->userProvider->getUser($this->options['field'], $username);
        if ($user && password_verify($password, $user->getPassword())) {
            $this->setUser($user);
            return $user;
        }
        return null;
    }

    /**
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete('auth.user');
        $this->user = null;
    }

    /**
     *
     * @param ResponseInterface $response
     * @param string $username
     * @param string $password
     * @param string $secret
     * @return ResponseInterface
     */
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
            ->withPath($this->options['path'])
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
        return FigResponseCookies::set($response, $cookie);

    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param string $secret
     * @return User|null
     */
    public function autoLogin(ServerRequestInterface $request, string $secret): ?User
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
        if ($cookie->getValue()) {
            list($username, $password) = AuthSecurityToken::decodeSecurityToken($cookie->getValue());
            $user = $this->userProvider->getUser($this->options['field'], $username);
            if ($user && AuthSecurityToken::validateSecurityToken(
                        $cookie->getValue(),
                        $username,
                        $user->getPassword(),
                        $secret
                    )
                ) {
                    $this->setUser($user);
                    return $user;
            }
        }
        return null;
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function onLogout(ServerRequestInterface $request,  ResponseInterface $response): ResponseInterface
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
        if ($cookie->getValue()) {
            $cookie = SetCookie::create($this->options['name'])
            ->withValue('')
            ->withExpires(time() - 3600)
            ->withPath($this->options['path'])
            ->withDomain(null)
            ->withSecure(false)
            ->withHttpOnly(false);
            $response = FigResponseCookies::set($response, $cookie);
        }
        return $response;
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function resume(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $cookie = FigRequestCookies::get($request, $this->options['name']);
        if ($cookie->getValue()) {
            $setCookie = SetCookie::create($this->options['name'])
                ->withValue($cookie->getValue())
                ->withExpires(time() + $this->options['expires'])
                ->withPath($this->options['path'])
                ->withDomain(null)
                ->withSecure(false)
                ->withHttpOnly(false);
                $response = FigResponseCookies::set($response, $setCookie);
        }
        return $response;
    }

    /**
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        $userId = $this->session->get('auth.user');

        if ($userId) {
            if ($this->user && (int) $this->user->getId() === (int) $userId) {
                return $this->user;
            }
            try {
                $this->user = $this->userProvider->getUser('id', $userId);
                return $this->user;
            } catch (Exception $e) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    /**
     * 
     * @param User $user
     * @return Auth
     */
    public function setUser(User $user): self
    {
        $this->session->set('auth.user', $user->getId());
        $this->user = $user;
        return $this;
    }
}
