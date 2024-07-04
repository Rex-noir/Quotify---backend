<?php

namespace Database\Factories;

use App\Models\User;
use App\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'quote' => fake()->sentence(),
            'author' => fake()->name(),
            'source' => fake()->url(),
            'status' => fake()->randomElement(PostStatus::all())
        ];
    }
}
