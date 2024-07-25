<?php

namespace App\Http\Controllers;

use App\Events\PostUpdated;
use App\Events\UserSpecificPostUpdates;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        $public = ['id' => $post->id,];

        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        $existingDislike = $post->dislikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
        } else {
            if ($existingDislike) {
                $existingDislike->delete();
            }
            $post->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);
            $isLiked = true;
        }

        $isDisliked = false; // After liking, user cannot have disliked the post

        // Get the updated counts
        $likesCount = $post->likes()->count();
        $dislikesCount = $post->dislikes()->count();

        // Prepare event data
        $public = array_merge($public, [
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        broadcast(new PostUpdated($public));

        if (Auth::check()) {
            $private = [
                'id' => $post->id,
                'is_liked_by_user' => $isLiked,
                'is_disliked_by_user' => $isDisliked,
            ];
            broadcast(new UserSpecificPostUpdates($private));
        }

        return response(204);
    }

    public function dislikePost(Request $request, $id)
    {
        $post = post::findOrFail($id);
        $user = Auth::user();

        $public = ['id' => $post->id,];

        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        $existingDislike = $post->dislikes()->where('user_id', $user->id)->first();

        if ($existingDislike) {
            // If the user already disliked the post, remove the dislike
            $existingDislike->delete();
            $isDisliked = false;
        } else {
            // If the user has liked the post, remove the like first
            if ($existingLike) {
                $existingLike->delete();
            }
            // Create a new dislike
            $post->dislikes()->create([
                'user_id' => $user->id,
                'is_like' => false,
            ]);
            $isDisliked = true;
        }

        $isLiked = false; // After disliking, user cannot have liked the post

        // Get the updated counts
        $likesCount = $post->likes()->count();
        $dislikesCount = $post->dislikes()->count();

        // Prepare event data
        $public = array_merge($public, [
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);

        broadcast(new PostUpdated($public));

        if (Auth::check()) {
            $private = [
                'id' => $post->id,
                'is_liked_by_user' => $isLiked,
                'is_disliked_by_user' => $isDisliked,
            ];
            broadcast(new UserSpecificPostUpdates($private));
        }

        return response(204);
    }
}
