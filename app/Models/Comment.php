<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'content', 'gif_url', 'parent_id'];

    protected $appends = ['is_liked_by_user', 'is_disliked_by_user',];

    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->with(['user'])
            ->orderby('created_at', 'asc');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->where('is_like', true);
    }

    public function dislikes(): HasMany
    {
        return $this->hasMany(CommentLike::class)->where('is_like', false);
    }

    public function getIsLikedByUserAttribute()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $this->relationLoaded('likes') 
            ? $this->likes->contains('user_id', $user->id)
            : $this->likes()->where('user_id', $user->id)->exists();
    }
    
    public function getIsDislikedByUserAttribute()
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $this->relationLoaded('dislikes')
        ? $this->dislikes->contains('user_id',$user->id)
        : $this->dislikes()->where('user_id',$user->id)->exists();
    }

}
