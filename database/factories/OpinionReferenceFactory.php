<?php

namespace Database\Factories;

use App\Models\Opinion;
use App\Models\OpinionReference;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OpinionReference>
 */
class OpinionReferenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opinionId = fake()->unique()->numberBetween(0, 100);
        $referToId = Opinion::where('id', '!=', $opinionId)->where('id', '<', 10)->inRandomOrder()->first()->id;

        return [
            'opinion_id' => $opinionId,
            'refer_to_id' => $referToId,
            'agree_type' => fake()->randomElement(['agree', 'disagree']),
        ];
    }
}
