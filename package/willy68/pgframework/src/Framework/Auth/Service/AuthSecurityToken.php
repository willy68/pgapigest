<?php

namespace Framework\Auth\Service;

class AuthSecurityToken
{
    const SEPARATOR = ':';

    public static function generateSecurityToken(
        string $username,
        string $password,
        string $security
    ): string
    {
        $username = base64_encode($username);
        $password = base64_encode(hash_hmac('sha256', $password, $security, true));
        return $username . self::SEPARATOR . $password;

    }

    public static function decodeSecurityToken(string $token): array
    {
        list($username, $password) = explode(self::SEPARATOR, $token);
        $username = base64_decode($username);
        $password = base64_decode($password);
        return [$username, $password];
    }

    public static function validateSecurityToken(
        string $token,
        string $username,
        string $password,
        string $security
    ): bool
    {
        $passwordToVerify = base64_encode(hash_hmac('sha256', $password, $security, true));
        list($usernameOrigin, $passwordOrigin) = self::decodeSecurityToken($token);
        if (
            hash_equals($passwordOrigin, $passwordToVerify) &&
            $usernameOrigin === $username
        ) {
            return true;
        }
        return false;
    }
}
