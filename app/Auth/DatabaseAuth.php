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

        /** @var \App\Auth\User $user */
        // $user = $this->userTable->findBy('username', $username);
        $user = User::find_by_username($username);
        if ($user && password_verify($password, $user->password)) {
            $this->session->set('auth.user', $user->getId());
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

    public function rememberMe(
        ResponseInterface $response,
        string $username,
        string $password,
        string $secret
    ): ResponseInterface
    {
        return $this->cookie->login($response, $username, $password, $secret);
    }

    public function rememberMeLogout(ResponseInterface $response): ResponseInterface
    {
        return $this->cookie->logout($response);
    }

    public function autoLogin(ServerRequestInterface $request, string $secret): ?AuthUser
    {
        if ($user = $this->getUser()) {
            return $user;
        }
        $this->user = $this->cookie->autoLogin($request, $secret);
        if ($this->user) {
            $this->session->set('auth.user', $this->user->getId());
            return $this->user;
        }
        return null;
    }
}
