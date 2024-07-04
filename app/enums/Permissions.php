<?php

namespace App\Enums;

enum Permissions: string
{
        //ACCESSES
    case ACCESS_ADMIN_PANEL = 'access admin panel';

        //plurals
    case CREATE_POSTS = 'create posts';
    case EDIT_ALL_POSTS = 'edit post';
    case DELETE_ALL_POSTS = 'delete_posts';
    case VIEW_ALL_POST = 'view_posts';
    case UPDATE_ALL_POSTS = 'update posts';

        //singulars
    case UPDATE_OWN_POSTS = 'update own posts';
    case EDIT_OWN_POSTS = 'edit own posts';
    case VIEW_OWN_POSTS = 'view own posts';
    case DELETE_OWN_POSTS = 'delete own posts';

    public function label(): string
    {
        return match ($this) {

            //plurals
            static::ACCESS_ADMIN_PANEL => 'Access to admin panel',
            static::CREATE_POSTS => 'Create posts',
            static::DELETE_ALL_POSTS => 'Delete all posts',
            static::EDIT_ALL_POSTS => 'Edit all posts',
            static::VIEW_ALL_POST => 'View all posts',
            static::UPDATE_ALL_POSTS => 'Update all posts',

            //singulars
            static::UPDATE_OWN_POSTS => 'Update own posts',
            static::EDIT_OWN_POSTS => 'Edit own posts',
            static::DELETE_OWN_POSTS => 'Delete own posts',
            static::VIEW_OWN_POSTS => 'View own posts',
        };
    }
}
