<?php

namespace Database\Factories;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{

    protected $model = Comment::class;

    public function definition()
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'story_id' => $this->faker->randomNumber(),
            'comment_text' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
