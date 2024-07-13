<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::with('user')
            ->withCount([
                'likes as likes_count',
                'disLikes as dislikes_count',
                'comments'
            ])
            ->orderByDesc('id')
            ->paginate(20);

        ($posts->getCollection()->transform(function ($post) {
            $comments = $post->comments()->withCount('likes as comment_likes_count')
                ->orderByDesc('comment_likes_count')
                ->latest()
                ->take(2)
                ->get();
            $post->setRelation('comments', $comments);
            return $post;
        }));

        return response($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $post = Post::query()->where('id', $id)
            ->with(['user'])
            ->withCount([
                'likes',
                'dislikes',
                'comments'
            ])
            ->first();

        if (!$post) {
            return response(['message' => 'Post not found'], 404);
        }
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function comments(string $id)
    {

        $comments = Comment::where([
            'post_id' => $id, 'parent_id' => null
        ])->with(['user',])
            ->withCount(['likes as likes_count', 'dislikes as dislikes_count'])
            ->paginate(10);


        foreach ($comments as $comment) {
            $comment->replies_count = $comment->repliesCount();
        }

        return $comments;
    }
}
