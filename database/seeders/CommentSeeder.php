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
        $posts = Post::all();

        foreach ($posts as $post) {

            $comment = Comment::factory()->for($post)->create();
            $another = Comment::factory(3)->for($post)->create();

            $level1Replies = Comment::factory()->for($post)->count(2)->create(['parent_id' => $comment->id]);

            foreach ($level1Replies as $level1Reply) {
                $level2Replies = Comment::factory()->for($post)->count(2)->create(['parent_id' => $level1Reply->id]);

                foreach ($level2Replies as $level2Reply) {
                    $replies =   Comment::factory()->for($post)->count(10)->create(['parent_id' => $level2Reply->id]);
                    foreach ($replies as $level3Reply) {
                        $replies =   Comment::factory()->for($post)->count(2)->create(['parent_id' => $level3Reply->id]);
                    }
                }
            }
        }
    }
}
