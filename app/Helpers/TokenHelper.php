<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenHelper
{
    public static function encode($user_uuid, $user_email)
    {
        //Generate token JWT
        $alg = "HS256";
        $key = config('app.JWT_SECRET_KEY');
        $payload = [
            'user_uuid' => $user_uuid,
            'email' => $user_email,
            'iat' => time(),
            'expired_at' => time() + (24 * 3600)
        ];

        return JWT::encode($payload, $key, $alg);
    }

    public static function decode($token)
    {
        // Decode Token JWT
        $alg = "HS256";
        $key = config('app.JWT_SECRET_KEY');
        return JWT::decode($token, new Key($key, $alg));
    }
}
