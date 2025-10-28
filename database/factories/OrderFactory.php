<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // try to find an existing user first before creating a new one
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'total_price' => 0.00,
            // Select a random status from the allowed enum values
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'cancelled']),
        ];
    }
}
