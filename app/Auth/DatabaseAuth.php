<?php

namespace App\Auth;

use ActiveRecord\RecordNotFound;
use App\Auth\Models\User;
use Framework\Auth;
use Framework\Auth\Service\RememberMeInterface;
use Framework\Auth\User as AuthUser;
use Framework\Session\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DatabaseAuth implements Auth
{

    /**
     * Undocumented variable
     *
     * @var SessionInterface
     */
    private $session;

    private $cookie;

    /**
     * Undocumented variable
     *
     * @var User
     */
    private $user;

    public function __construct(SessionInterface $session, RememberMeInterface $cookie)
    {
        $this->session = $session;
        $this->cookie = $cookie;
    }

    /**
     * Undocumented function
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

        // $user = $this->userTable->findBy('username', $username);
        /** @var User $user */
        $user = User::find_by_username($username);
        if ($user && password_verify($password, $user->password)) {
            $this->setUser($user);
            return $user;
        }
        return null;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->delete('auth.user');
    }

        /**
     * Undocumented function
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
                // $this->user = $this->userTable->find((int) $userId);
                $this->user = User::find((int) $userId);
                return $this->user;
            } catch (RecordNotFound $e) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    /**
     * 
     * @param AuthUser $user
     * @return Auth
     */
    public function setUser(AuthUser $user): self
    {
        $this->session->set('auth.user', $user->getId());
        $this->user = $user;
        return $this;
    }

    public function rememberMe(
        ResponseInterface $response,
        string $username,
        string $password,
        string $secret
    ): ResponseInterface
    {
        return $this->cookie->onLogin($response, $username, $password, $secret);
    }

    public function rememberMeLogout(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->cookie->onLogout($request, $response);
    }

    public function autoLogin(ServerRequestInterface $request, string $secret): ?AuthUser
    {
        $user = $this->cookie->autoLogin($request, $secret);
        if ($user) {
            $this->setUser($user);
            return $user;
        }
        return null;
    }

    public function resume(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->cookie->resume($request, $response);
    }
}
