<?php

namespace App\Auth;

use Framework\Auth;
use Framework\Auth\User;
use App\Auth\Table\UserTable;
use Framework\Database\NoRecordException;
use Framework\Session\SessionInterface;

class DatabaseAuth implements Auth
{

    /**
     * Undocumented variable
     *
     * @var UserTable
     */
    private $userTable;

    /**
     * Undocumented variable
     *
     * @var SessionInterface
     */
    private $session;

    /**
     * Undocumented variable
     *
     * @var \App\Auth\User
     */
    private $user;

    /**
     * Undocumented function
     *
     * @param UserTable $userTable
     */
    public function __construct(UserTable $userTable, SessionInterface $session)
    {
        $this->userTable = $userTable;
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

        /** @var \App\Auth\User $user */
        $user = $this->userTable->findBy('username', $username);
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
                $this->user = $this->userTable->find((int) $userId);
                return $this->user;
            } catch (NoRecordException $e) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }
}
