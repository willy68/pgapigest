<?php

namespace Framework\Auth\Service;

class AuthSecurityValue
{
    const SEPARATOR = ':';

    public static function generateSecurityValue(string $username, string $password, string $security): string
    {
        $username = base64_encode($username);
        $password = base64_encode(hash_hmac('sha256', $password, $security));
        return "$username${self::SEPARATOR}$password";

    }

    public static function decodeSecurityValue(string $value): array
    {
        list($username, $password) = explode(self::SEPARATOR, $value);
        $username = base64_decode($username);
        $password = base64_decode($password);
        return [$username, $password];
    }
}
