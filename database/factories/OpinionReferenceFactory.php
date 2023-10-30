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
        $opinionId = fake()->unique()->numberBetween(1, 100);
        $opinion = Opinion::where('id', $opinionId)->first();
        $referToId = Opinion::where('id', '!=', $opinionId)->where('topic_id', $opinion->topic_id)->inRandomOrder()->first()->id;

        return [
            'opinion_id' => $opinionId,
            'refer_to_id' => $referToId
        ];
    }
}
