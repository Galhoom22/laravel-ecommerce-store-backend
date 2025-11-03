<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartItemModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cart_item_belongs_to_cart(): void
    {
        // Arrange
        $cartItem = CartItem::factory()->create();

        // Act
        $relatedCart = $cartItem->cart;

        // Assert
        $this->assertInstanceOf(Cart::class, $relatedCart);
    }

    /** @test */
    public function test_cart_item_belongs_to_product(): void
    {
        // Arrange
        $cartItem = CartItem::factory()->create();

        // Act
        $relatedProduct = $cartItem->product;

        // Assert
        $this->assertInstanceOf(Product::class, $relatedProduct);
    }

    /** @test */
    public function test_unique_constraint_prevents_duplicate_products(): void
    {
        // Arrange
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100.00,
        ]);

        // Act & Assert
        $this->expectException(QueryException::class);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100.00,
        ]);
    }
}
