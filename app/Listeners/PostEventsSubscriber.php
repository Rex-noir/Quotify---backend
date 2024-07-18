<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Events\LikeDislikeClicked;
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

    public function handleLikeDislikeClicked(LikeDislikeClicked $event)
    {
        try {
            $post = Post::findOrFail($event->postId);
            $changes = $event->likeOrDislike;

            $updates = [
                'id' => $event->postId,
            ];

            if ($changes['is_like']) {
                $updates = array_merge($updates, ['likes_count' => $post->likes()->count()]);
            }

            if ($changes['is_dislike']) {
                $updates = array_merge($updates, ['dislikes_count' => $post->dislikes()->count()]);
            }

            event(new PostUpdated($updates));
        } catch (\Throwable $th) {
            Log::error('Error handling LikeDislikeClicked event' . $th->getMessage());
        }
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            CommentAdded::class => 'handleCommentAdded',
            LikeDislikeClicked::class => 'handleLikeDislikeClicked'
        ];
    }
}
