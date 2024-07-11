<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::with('user')
            ->withCount([
                'likes as likes_count' => function ($query) {
                    $query->where('is_like', true);
                },
                'disLikes as dislikes_count' => function ($query) {
                    $query->where('is_like', false);
                },
                'comments as comments_count'
            ])
            ->orderByDesc('created_at')
            ->paginate(20);

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
        $post = Post::query()->where('id', $id)->with('user')->first();

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
}
