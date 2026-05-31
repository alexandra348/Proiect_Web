<?php

require_once __DIR__ . '/../utils/JWTUtils.php';

use Utils\JWTUtils;

class AuthMiddleware
{
    public static function authenticate()
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            throw new Exception('Missing authorization header');
        }

        $authHeader = $headers['Authorization'];

        if (!str_starts_with($authHeader, 'Bearer ')) {
            throw new Exception('Invalid authorization format');
        }

        $token = substr($authHeader, 7);

        $jwt = new JWTUtils();

        return $jwt->validateToken($token);
    }
}