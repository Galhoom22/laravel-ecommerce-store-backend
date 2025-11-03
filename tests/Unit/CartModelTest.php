<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cart;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_cart_belongs_to_user(): void
    {
        // Arrange
        $cart = Cart::factory()->create();

        // Act
        $relatedUser = $cart->user;

        // Assert
        $this->assertInstanceOf(User::class, $relatedUser);
    }

    /** @test */
    public function test_cart_has_many_items(): void
    {
        // Arrange
        $cart = Cart::factory()
            ->has(CartItem::factory()->count(3), 'items')
            ->create();

        // Act
        $items = $cart->items;

        // Assert
        $this->assertCount(3, $items);
        $this->assertInstanceOf(CartItem::class, $items->first());
    }

    /** @test */
    public function test_cart_cascades_delete_items(): void
    {
        // Arrange
        $cart = Cart::factory()
            ->has(CartItem::factory()->count(2), 'items')
            ->create();

        // Act
        $cart->delete();

        // Assert
        $this->assertDatabaseMissing('cart_items', ['cart_id' => $cart->id]);
    }
}
