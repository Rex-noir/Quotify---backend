<?php

namespace App\Enums;

enum Permissions: string
{
    case CREATE_POST = 'create post';
    case EDIT_POST = 'edit post';
    case DELETE_POST = 'delete_post';
    case VIEW_POST = 'view_post';
    case ACCESS_ADMIN_PANEL = 'access admin panel';
    case UPDATE_POST = 'update post';

    public function label(): string
    {
        return match ($this) {
            static::ACCESS_ADMIN_PANEL => 'Access to admin panel',
            static::CREATE_POST => 'Create post',
            static::DELETE_POST => 'Delete post',
            static::EDIT_POST => 'Edit post',
            static::VIEW_POST => 'View post',
            static::UPDATE_POST => 'Update post'
        };
    }
}
