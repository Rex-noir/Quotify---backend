<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'title', 'quote', 'author', 'source', 'status',];


    protected $appends = ['is_liked_by_user', 'is_disliked_by_user', 'top_comments'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class)->where('is_like', true);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(Like::class)->where('is_like', false);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getIsLikedByUserAttribute()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getIsDislikedByUserAttribute()
    {
        $authUser = auth()->user();
        if (!$authUser) {
            return false;
        }
        return $this->dislikes()->where('user_id', $authUser->id)->exists();;
    }

    public function getTopCommentsAttribute()
    {
        return $this->comments()->withCount('likes as comment_likes_count')
            ->orderByDesc('comment_likes_count')
            ->latest()
            ->take(2)
            ->get();
    }
}
