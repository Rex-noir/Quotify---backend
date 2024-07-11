<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
it('Can retrieve replies for a comment', function () {

    $user = User::factory()->create();

    $post = Post::factory()->create();

    $parentComment = Comment::factory()->create(['post_id' => $post->id, 'user_id' => $user->id]);

    $replies = Comment::factory(4)->create(['post_id' => $post->id, 'user_id' => $user->id, 'parent_id' => $parentComment->id]);

    $retrievedReplies = $parentComment->replies;

    expect($retrievedReplies)->toHaveCount(4);

    foreach ($replies as $reply) {
        expect($retrievedReplies)->contains($reply)->toBeTrue();
    }
});
