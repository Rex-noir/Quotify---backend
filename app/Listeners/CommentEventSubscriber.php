<?php

namespace App\Listeners;

use App\Events\CommentAdded;
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
            $public = ['replies_count' => $parentComment->replies()->count(), 'id' => $comment['parent_id'], 'post_id' => $comment['post_id']];
            broadcast(new CommentUpdated($public))->toOthers();
        }
    }

    public function subscribe(Dispatcher $events)
    {

        return [
            CommentAdded::class => 'handleCommentAdded',
        ];
    }
}
