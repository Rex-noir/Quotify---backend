<?php

namespace App\Enums;

enum Roles: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
    case SUPER_ADMIN = 'super admin';

    public function label(): string
    {
        return match ($this) {
            static::USER => 'User',
            static::ADMIN => 'Admin',
            static::MODERATOR => 'Moderator',
            static::SUPER_ADMIN => 'Super Admin',
        };
    }
}
