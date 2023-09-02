<?php

namespace Database\Factories;

use App\Models\Opinion;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Opinion>
 */
class OpinionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic_id' => Topic::all()->random(),
            'user_id' => User::all()->random(),
            'title' => fake()->paragraph(1),
            'agree_type' => fake()->randomElement(['agree', ['disagree']]),
            'like' => fake()->randomDigit(),
            'dislike' => fake()->randomElement(),
        ];
    }
}
