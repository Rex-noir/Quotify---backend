<?php

namespace App\Http\Controllers;

use App\Events\CommentLikeDislikeClicked;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    //
    public function likeComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        $existingDislike = $comment->dislikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            // If the user already liked the comment, remove the like
            $existingLike->delete();
            $eventData = ['is_like' => false, 'is_dislike' => false];
        } else {
            // If the user has disliked the comment, remove the dislike first
            if ($existingDislike) {
                $existingDislike->delete();
            }
            // Create a new like
            $comment->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);

            $eventData = (['is_like' => true, 'is_dislike' => false]);
        }

        CommentLikeDislikeClicked::dispatch($eventData, $comment->id);

        return response()->noContent();
    }

    public function dislikeComment(Request $request, $id)
    {
        $comment = comment::findOrFail($id);
        $user = Auth::user();

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        $existingDislike = $comment->dislikes()->where('user_id', $user->id)->first();

        if ($existingDislike) {
            // If the user already disliked the comment, remove the dislike
            $existingDislike->delete();
            $eventData = ['is_like' => false, 'is_dislike' => false];
        } else {
            // If the user has liked the comment, remove the like first
            if ($existingLike) {
                $existingLike->delete();
            }
            // Create a new dislike
            $comment->dislikes()->create([
                'user_id' => $user->id,
                'is_like' => false,
            ]);
            $eventData = ['is_like' => false, 'is_dislike' => true];
        }

        CommentLikeDislikeClicked::dispatch($eventData, $comment->id);

        return response()->noContent();
    }
}
