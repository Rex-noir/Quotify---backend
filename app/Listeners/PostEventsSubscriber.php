<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Events\PostUpdated;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class PostEventsSubscriber
{

    public function handleCommentAdded(CommentAdded $event)
    {
        $comment = $event->comment;
        $post = Post::find($comment->post_id);

        $updates = ['comments_count' => $post->comments->count(), 'id' => $post->id];

        event(new PostUpdated($updates));
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CommentAdded::class => 'handleCommentAdded',
        ];
    }
}
