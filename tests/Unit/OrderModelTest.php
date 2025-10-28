<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;
    public function test_order_belongs_to_user(): void
    {
        // Arrange: create a user
        $user = User::factory()->create();

        // Act: create an order for this user
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        // Assert: check that order->user return the correct user
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    public function test_order_has_many_order_items(): void
    {
        // Arrange
        // create an order
        $order = Order::factory()->create();

        // create a category first because products needs categories
        $category = Category::factory()->create();

        // create a product with this category
        $product = Product::factory()->create([
            'category_id' => $category->id
        ]);

        // Act: create 3 order items for this order
        OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        // Assert: check that order has 3 items
        $this->assertCount(3, $order->orderItems);
    }

    public function test_order_can_access_products_through_pivot(): void
    {
        // Arrange: create order and category and 2 products
        $order = Order::factory()->create();
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        // Act: create order items to link order with the 2 products
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
        ]);

        // Assert: order->products should return 2 products
        $this->assertCount(2, $order->products);
        $this->assertTrue($order->products->contains($product1));
        $this->assertTrue($order->products->contains($product2));
    }
}
