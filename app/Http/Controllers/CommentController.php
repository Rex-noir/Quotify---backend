<?php

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $comments = Comment::with(['user', 'post', 'parent', 'replies', 'likes', 'dislikes'])
        //     ->withCount(['likes as likes_count', 'dislikes as dislikes_count'])
        //     ->paginate(10);

        // return $comments;
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
        $validated = $request->validate([
            'content' => 'required',
            'post_id' => 'required|integer',
            'parent_id' => 'integer|nullable'
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'post_id' => $validated['post_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => Auth::user()->id,
        ]);

        broadcast(new  CommentAdded($comment))->toOthers();
        return response(['comment' => $comment]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $comment = Comment::with(['user',])->findOrFail($id);
        $comment->replies_count = $comment->repliesCount();
        return $comment;
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

    public function replies(string $id)
    {
        $replies = Comment::where('parent_id', $id)->with(['user'])->get();
        foreach ($replies as $comment) {
            $comment->replies_count = $comment->repliesCount();
        }
        return response()->json($replies);
    }
}
