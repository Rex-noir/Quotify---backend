<?php

namespace App;

enum PostStatus: string
{
        //
    case APPROVED = 'approved';
    case REMOVED = 'removed';
    case REPORTED = 'reported';
    case PENDING = 'pending';

    public static function all(): array
    {
        return [
            self::APPROVED->value,
            self::REPORTED->value,
            self::PENDING->value,
            self::REMOVED->value
        ];
    }
}
