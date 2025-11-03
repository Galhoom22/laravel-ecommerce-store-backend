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

    /**
     * Test: Order belongs to a user.
     */
    public function test_order_belongs_to_user(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        // Assert
        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /**
     * Test: Order has many items.
     */
    public function test_order_has_many_items(): void
    {
        // Arrange
        $order = Order::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        // Act
        OrderItem::factory()->count(3)->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        // Assert
        $this->assertCount(3, $order->items);
    }

    /**
     * Test: Order can access its products through items relationship.
     */
    public function test_order_can_access_products_through_items(): void
    {
        // Arrange
        $order = Order::factory()->create();
        $category = Category::factory()->create();

        $product1 = Product::factory()->create(['category_id' => $category->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id]);

        // Act
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product1->id,
        ]);

        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product2->id,
        ]);

        // Assert
        $this->assertCount(2, $order->items);
        $productIds = $order->items->pluck('product_id');
        $this->assertTrue($productIds->contains($product1->id));
        $this->assertTrue($productIds->contains($product2->id));
    }
}
