<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RoleSeeder::class);

        User::factory()->withRole(Roles::SUPER_ADMIN)->create([
            'name' => 'Test User',
            'username' => 'test.user',
            'email' => 'test@example.com',
        ]);

        User::factory(10)->has(Post::factory(4))->withRole(Roles::USER)->create();
        $this->call(LikeSeeder::class);
        $this->call(TagSeeder::class);
        // $this->call(CommentSeeder::class);
        // $this->call(CommentLikeSeeder::class);
    }
}
