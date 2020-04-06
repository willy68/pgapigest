<?php

namespace App\Auth;

use Framework\Auth\User as AuthUser;

class User implements AuthUser
{
    public $id;
    
    public $username;

    public $email;

    public $password;

    private $roles = [];

        /**
     * Undocumented function
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Undocumented function
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
