<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderItemModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: OrderItem belongs to an Order.
     */
    public function test_order_item_belongs_to_order(): void
    {
        // Arrange
        $order = Order::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        // Act
        $relatedOrder = $orderItem->order;

        // Assert
        $this->assertInstanceOf(Order::class, $relatedOrder);
        $this->assertEquals($order->id, $relatedOrder->id);
    }

    /**
     * Test 2: OrderItem belongs to a Product.
     */
    public function test_order_item_belongs_to_product(): void
    {
        // Arrange
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);
        $order = Order::factory()->create();

        $orderItem = OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
        ]);

        // Act
        $relatedProduct = $orderItem->product;

        // Assert
        $this->assertInstanceOf(Product::class, $relatedProduct);
        $this->assertEquals($product->id, $relatedProduct->id);
    }

    /**
     * Test 3: OrderItem has correct attributes (quantity & price).
     */
    public function test_order_item_has_correct_attributes(): void
    {
        // Arrange
        $order = Order::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $orderItem = OrderItem::factory()->create([
            'order_id'  => $order->id,
            'product_id' => $product->id,
            'quantity'  => 5,
            'price'     => 199.99,
        ]);

        // Act
        $freshItem = OrderItem::find($orderItem->id);

        // Assert
        $this->assertEquals(5, $freshItem->quantity);
        $this->assertEquals(199.99, $freshItem->price);
    }
}
