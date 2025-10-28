<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // attempt to find a random existing product
        $product = Product::inRandomOrder()->first();
        return [
            'order_id' => Order::factory(),
            'product_id' => $product?->id, // Use null safe operator
            'quantity' => fake()->numberBetween(1, 5),
            'price' => 0.00,
        ];
    }
}
