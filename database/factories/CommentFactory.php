<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'article_id' => Article::factory(),
            'user_id' => User::factory(),
            'content' => fake()->paragraph(),
            'is_approved' => fake()->boolean(70),
        ];
    }
}