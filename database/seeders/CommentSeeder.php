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
        // Fetch all posts in chunks to handle large data sets efficiently
        Post::chunk(5, function ($posts) {
            foreach ($posts as $post) {
                // Create an initial comment for the post
                $initialComments = Comment::factory()->count(5)->for($post)->create();

                // For each initial comment, create replies
                foreach ($initialComments as $comment) {
                    $this->createReplies($post, $comment, 3);
                }
            }
        });
    }

    /**
     * Recursive function to create replies for a comment.
     *
     * @param Post $post
     * @param Comment $comment
     * @param int $depth
     */
    protected function createReplies(Post $post, Comment $comment, int $depth)
    {
        if ($depth <= 0) {
            return;
        }

        // Create a number of replies for the given comment
        $replies = Comment::factory()->count(5)->for($post)->create(['parent_id' => $comment->id]);

        foreach ($replies as $reply) {
            // Recursively create replies for each reply
            $this->createReplies($post, $reply, $depth - 1);
        }
    }
}
