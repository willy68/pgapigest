<?php

namespace App\Auth\Models;

use ActiveRecord\Model;
use Framework\Auth\User as AuthUser;

class User extends Model implements  AuthUser
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
