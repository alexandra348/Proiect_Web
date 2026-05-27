<?php

namespace Utils;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTUtils
{
    private string $secret;
    private int $expiry;

    public function __construct()
    {
        $config = require __DIR__.'/../config/jwt.php';

        $this->secret = $config['secret'];
        $this->expiry = $config['expiry'];
    }

    public function generateToken(array $user): string
    {
        $payload = [
            "user_id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"],
            "iat" => time(),
            "exp" => time() + $this->expiry
        ];

        return JWT::encode(
            $payload,
            $this->secret,
            'HS256'
        );
    }

    public function validateToken(string $token)
    {
        return JWT::decode(
            $token,
            new Key(
                $this->secret,
                'HS256'
            )
        );
    }
}