<?php

namespace Framework;

// use Framework\Auth\User;
use Framework\Auth\Models\User;

interface Auth
{

    /**
     *
     *
     * @return User|null
     */
    public function getUser(): ?User;
}
