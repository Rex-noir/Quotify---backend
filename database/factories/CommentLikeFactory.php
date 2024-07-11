<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentLike>
 */
class CommentLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::inRandomOrder()->first();
        $comment = Comment::inRandomOrder()->first();
        return [
            //
            'comment_id' => $comment->id,
            'user_id' => $user->id,
            'is_like' => fake()->boolean(70)
        ];
    }
}
