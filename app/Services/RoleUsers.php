<?php

namespace App\Services;

class RoleUsers
{
    const ADMIN = 'admin';
    const USER  = 'user';

    public static function role(): array {
        return [
            self::ADMIN,
            self::USER,
        ];
    }
}
