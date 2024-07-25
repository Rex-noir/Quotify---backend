<?php

namespace App\Http\Controllers;

use App\Events\CommentUpdated;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentLikeController extends Controller
{
    public function likeComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        $public = ['post_id' => $comment->post_id, 'id' => $comment->id,];

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        $existingDislike = $comment->dislikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
        } else {
            if ($existingDislike) {
                $existingDislike->delete();
            }
            $comment->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);
            $isLiked = true;
        }

        $isDisliked = false; // After liking, user cannot have disliked the comment

        // Get the updated counts
        $likesCount = $comment->likes()->count();
        $dislikesCount = $comment->dislikes()->count();

        // Prepare event data
        $public = array_merge($public, [
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        $private = [
            'is_liked_by_user' => $isLiked,
            'is_disliked_by_user' => $isDisliked,
            'user_id'=>$user->id,
        ];

        // Broadcast the updated data
        broadcast(new CommentUpdated($public, $private));

        // Return the updated data
        return response(204);
    }

    public function dislikeComment(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $user = Auth::user();

        $public = ['post_id' => $comment->post_id, 'id' => $comment->id,];

        $existingLike = $comment->likes()->where('user_id', $user->id)->first();
        $existingDislike = $comment->dislikes()->where('user_id', $user->id)->first();

        if ($existingDislike) {
            // If the user already disliked the comment, remove the dislike
            $existingDislike->delete();
            $isDisliked = false;
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
            $isDisliked = true;
        }

        $isLiked = false; // After disliking, user cannot have liked the comment

        // Get the updated counts
        $likesCount = $comment->likes()->count();
        $dislikesCount = $comment->dislikes()->count();

        // Prepare event data
        $public = array_merge($public, [
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        $private = [
            'is_liked_by_user' => $isLiked,
            'is_disliked_by_user' => $isDisliked,
            'user_id'=>$user->id,
        ];

        // Broadcast the updated data
        broadcast(new CommentUpdated($public, $private));

        // Return the updated data
        return response(204);
    }
}
