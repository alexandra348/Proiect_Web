<?php

require_once __DIR__ . '/AuthMiddleware.php';
require_once __DIR__ . '/RoleMiddleware.php';

class Authorization
{
    public static function requireRoles(array $roles)
    {
        try {

            $user = AuthMiddleware::authenticate();

            RoleMiddleware::requireRoles(
                $user,
                $roles
            );

            return $user;

        } catch (Exception $e) {

            sendResponse([
                "status" => 401,
                "error_code" => "UNAUTHORIZED",
                "message" => $e->getMessage()
            ]);
        }
    }
}