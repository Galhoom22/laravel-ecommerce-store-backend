<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. get all existing users
        $users = User::all();

        // 2. get all existing products
        $products = Product::all();

        // check if we have users and products to avoid errors
        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('please create users and products first');
            return; // stop the seeder if no users or products exist
        }

        // 3. start the loop to create 5 orders
        for ($i = 0; $i < 5; $i++) {
            $randomUser = $users->random();
            $order = Order::factory()->create([
                'user_id' => $randomUser->id,
                'status' => 'pending'
            ]);

            $numberOfItems = rand(2, 4);
            $totalPrice = 0;

            // 4. loop to create order items
            for($j = 0; $j < $numberOfItems; $j++){
                $randomProduct = $products->random();

                // calculate item total
                $quantity = rand(1, 3);
                $itemPrice = $randomProduct->price;
                $itemTotal = $itemPrice * $quantity;
                $totalPrice += $itemTotal;

                // create the order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $randomProduct->id,
                    'quantity' => $quantity,
                    'price' => $itemPrice,
                    
                ]);
            }
            $order->update(['total_price' => $totalPrice]);
        }

    }
}
