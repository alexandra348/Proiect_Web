<?php

class RoleMiddleware
{
    public static function requireRoles($user, array $roles)
    {
        if (!in_array($user->role, $roles)) {
            throw new Exception("Access denied");
        }
    }
}