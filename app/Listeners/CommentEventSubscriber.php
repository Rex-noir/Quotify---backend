<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Events\CommentLikeDislikeClicked;
use App\Events\CommentUpdated;
use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CommentEventSubscriber
{
    public function handleCommentAdded(CommentAdded $event)
    {
        $comment = $event->comment;
        if ($comment && isset($comment->parent_id)) {
            $parentComment = Comment::findOrFail($comment['parent_id']);
            $updates = ['replies_count' => $parentComment->replies()->count(), 'id' => $comment['parent_id'], 'post_id' => $comment['post_id']];
            broadcast(new CommentUpdated($updates))->toOthers();
        }
    }

    public function handleCommentLikeDislikeClicked(CommentLikeDislikeClicked $event)
    {
        try {
            $comment = Comment::findOrFail($event->commentId);
            $changes = $event->likeOrDislike;

            $updates = [
                'id' => $event->commentId,
                'post_id' => $comment->post_id,
            ];

            if ($changes['is_like']) {
                $updates = array_merge($updates, ['likes_count' => $comment->likes()->count()]);
            }

            if ($changes['is_dislike']) {
                $updates = array_merge($updates, ['dislikes_count' => $comment->dislikes()->count()]);
            }

            event(new CommentUpdated($updates));
        } catch (\Throwable $th) {
            Log::error('Error handling LikeDislikeClicked event' . $th->getMessage());
        }
    }

    public function subscribe(Dispatcher $events)
    {

        return [
            CommentAdded::class => 'handleCommentAdded',
            CommentLikeDislikeClicked::class => 'handleCommentLikeDislikeClicked'
        ];
    }
}
