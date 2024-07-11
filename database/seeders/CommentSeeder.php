<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $posts = Post::all();

        foreach ($posts as $post) {
            $comment = Comment::factory()->for($post)->create();

            Comment::factory()->for($post)->count(3)->create(['parent_id' => $comment->id]);
        }
    }
}
