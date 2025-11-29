<?php

namespace Database\Factories;

use App\Models\User;
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
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraphs(3, true),
            'likes' => $this->faker->numberBetween(0, 500),
            'dislikes' => $this->faker->numberBetween(0, 500),
            'views' => $this->faker->numberBetween(0, 1000),
            'user_id' => User::factory(),
        ];
    }
}
