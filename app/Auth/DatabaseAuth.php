<?php

namespace App\Auth;

use ActiveRecord\RecordNotFound;
use App\Auth\Models\User;
use Framework\Auth;
use Framework\Session\SessionInterface;

class DatabaseAuth implements Auth
{

    /**
     * Undocumented variable
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * Undocumented variable
     *
     * @var \App\Auth\Models\User
     */
    private $user;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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

        /* @var \App\Auth\User $user */
        // $user = $this->userTable->findBy('username', $username);
        $user = User::find_by_username($username);
        if ($user && password_verify($password, $user->password)) {
            $this->session->set('auth.user', $user->id);
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
            if ($this->user && (int) $this->user->id === (int) $userId) {
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
}
