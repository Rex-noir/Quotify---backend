<?php

namespace Database\Seeders;

use App\Enums\Permissions;
use App\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = Permissions::cases();
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission->value]);
        }

        $admin = Role::create(['name' => Roles::ADMIN->value]);
        $user = Role::create(['name' => Roles::USER->value]);
        $moderator = Role::create(['name' => Roles::MODERATOR->value]);
        $super_admin = Role::create(['name' => Roles::SUPER_ADMIN->value]);

        $super_admin->givePermissionTo([
            Permissions::ACCESS_ADMIN_PANEL,
            Permissions::CREATE_POSTS,
            Permissions::DELETE_ALL_POSTS,
            Permissions::EDIT_ALL_POSTS,
            Permissions::UPDATE_ALL_POSTS,
            Permissions::VIEW_ALL_POST
        ]);

        $user->givePermissionTo([
            Permissions::CREATE_POSTS,
            Permissions::EDIT_OWN_POSTS,
            Permissions::UPDATE_OWN_POSTS,
            Permissions::DELETE_OWN_POSTS,
            Permissions::VIEW_OWN_POSTS
        ]);
    }
}
