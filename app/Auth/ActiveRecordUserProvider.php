<?php

namespace App\Auth;

use App\Auth\Models\User;
use Framework\Auth\User as AuthUser;
use Framework\Auth\Provider\UserProvider;

class ActiveRecordUserProvider implements UserProvider
{
    /**
     * 
     * @var User
     */
    protected $model = User::class;

    public function getUser(string $field, $value): ?AuthUser
    {
        $user = $this->model::find(['conditions' => "$field = $value"]);
        if ($user) {
            return $user;
        }
        return null;
    }
}
