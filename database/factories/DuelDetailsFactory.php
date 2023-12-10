<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class DuelDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'round' => 1,
            'your_points' => 1,
            'opponent_points' => 1,
            'your_card_id' => 1,
            'opponent_card_id' => 1,
            'duel_id' => 1,
        ];
    }
}
