<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentLikeDislikeClicked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $likeOrDislike;
    public $commentId;

    /**
     * Create a new event instance.
     *
     * @param array $likeOrDislike An array containing two booleans: ['is_like', 'is_dislike']
     * @param int $commentId
     */
    public function __construct($likeOrDislike, $commentId)
    {
        //
        $this->commentId = $commentId;
        $this->likeOrDislike = $likeOrDislike;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('comments'),
        ];
    }
}
