<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Events\PostUpdated;
use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PostEventsSubscriber
{

    public function handleCommentAdded(CommentAdded $event)
    {
        try {
            $comment = $event->comment;
            $post = Post::findOrFail($comment->post_id);

            $updates = ['comments_count' => $post->comments->count(), 'id' => $post->id];

            event(new PostUpdated($updates));
        } catch (\Throwable $th) {
            Log::error("Error handling CommentAdded event" . $th->getMessage());
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CommentAdded::class => 'handleCommentAdded',
        ];
    }
}
