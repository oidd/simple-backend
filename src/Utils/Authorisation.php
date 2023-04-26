<?php

namespace App\Utils;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class Authorisation
{
    public static string $userName;

    public static function createToken($login)
    {
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+20 minutes')->getTimestamp();

        $data = [
            'iat'       => $issuedAt->getTimestamp(),         // Issued at
            'nbf'       => $issuedAt->getTimestamp(),         // Not before
            'exp'       => $expire,                           // Expire
            'userName'  => $login                             // User name
        ];

        return JWT::encode(
            $data,
            $_ENV['secret_key'],
            'HS512'
        );
    }

    public static function validateToken($token)
    {
        try {
            $token = JWT::decode($token, new Key($_ENV['secret_key'], 'HS512'));
            self::$userName = $token->userName;
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }
}
