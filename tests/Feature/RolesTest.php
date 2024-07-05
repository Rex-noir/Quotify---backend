<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
    $this->user = User::factory()->create();
    $this->role = Role::create(['name' => 'admin']);
    $this->permission = Permission::create(['name' => 'edit articles']);
});

test('Users have roles', function () {
    $this->user->assignRole($this->role);
    expect($this->user->hasRole('admin'))->toBeTrue();
});

test('User have permission', function () {
    $this->role->givePermissionTo($this->permission);
    $this->user->assignRole($this->role);
    expect($this->user->can('edit articles'))->toBeTrue();
});
