<?php

namespace App\Auth;

use App\Auth\User;
use App\Auth\Table\UserTable;
use Framework\Auth\User as AuthUser;
use Framework\Auth\Provider\UserProvider;
use Framework\Database\NoRecordException;

class DatabaseUserProvider implements UserProvider
{

    private $userTable;

    public function __construct(UserTable $userTable)
    {
        $this->userTable = $userTable;
    }

    public function getUser(string $field, $value): ?AuthUser
    {
        try {
            /** @var User $user */
            $user = $this->userTable->findBy($field, $value);
        } catch (NoRecordException $e) {
            return null;
        }
        return $user;
    }
}
