<?php

namespace App\Http\Controllers;

use App\Events\LikeDislikeClicked;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    //
    public function likePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        $existingDislike = $post->dislikes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            // If the user already liked the post, remove the like
            $existingLike->delete();
            $eventData = ['is_like' => false, 'is_dislike' => false];
        } else {
            // If the user has disliked the post, remove the dislike first
            if ($existingDislike) {
                $existingDislike->delete();
            }
            // Create a new like
            $post->likes()->create([
                'user_id' => $user->id,
                'is_like' => true,
            ]);

            $eventData = (['is_like' => true, 'is_dislike' => false]);
        }

        LikeDislikeClicked::dispatch($eventData, $post->id);

        return response()->noContent();
    }

    public function dislikePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $user = Auth::user();

        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        $existingDislike = $post->dislikes()->where('user_id', $user->id)->first();

        if ($existingDislike) {
            // If the user already disliked the post, remove the dislike
            $existingDislike->delete();
            $eventData = ['is_like' => false, 'is_dislike' => false];
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
            $eventData = ['is_like' => false, 'is_dislike' => true];
        }

        LikeDislikeClicked::dispatch($eventData, $post->id);

        return response()->noContent();
    }
}
