<?php

namespace App\Auth\Models;

use Framework\Auth\Models\User as AuthUser;

class User extends AuthUser
{
    public static $connection = 'blog';

    public static $table_name = 'users';

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
