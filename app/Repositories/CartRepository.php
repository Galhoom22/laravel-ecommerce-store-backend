<?php

namespace App\Repositories;

use App\Contracts\Repositories\CartRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;

/**
 * Class CartRepository
 *
 * Handles all data persistence and retrieval logic for Cart and CartItem.
 * Implements Repository Pattern to separate database access from business logic.
 */
class CartRepository implements CartRepositoryInterface
{
    /**
     * Find the user's cart by user ID.
     *
     * @param int $userId
     * @return Cart|null
     */
    public function findByUserId(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)->first();
    }

    /**
     * Create a new cart for the given user.
     *
     * @param int $userId
     * @return Cart
     */
    public function createForUser(int $userId): Cart
    {
        return Cart::create(['user_id' => $userId]);
    }

    /**
     * Add a new item to the user's cart.
     *
     * @param Cart $cart
     * @param int $productId
     * @param int $quantity
     * @param float $price
     * @return CartItem
     */
    public function addItem(Cart $cart, int $productId, int $quantity, float $price): CartItem
    {
        return $cart->items()->create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Update the quantity of an existing cart item.
     *
     * @param CartItem $item
     * @param int $quantity
     * @return CartItem
     */
    public function updateItemQuantity(CartItem $item, int $quantity): CartItem
    {
        $item->update(['quantity' => $quantity]);
        return $item->fresh(); // return updated model instance
    }

    /**
     * Remove a specific item from the cart.
     *
     * @param CartItem $item
     * @return bool
     */
    public function removeItem(CartItem $item): bool
    {
        return (bool) $item->delete();
    }

    /**
     * Clear all items from the given cart.
     *
     * @param Cart $cart
     * @return bool
     */
    public function clearCart(Cart $cart): bool
    {
        return (bool) $cart->items()->delete();
    }

    /**
     * Calculate the total price of all items in the cart.
     *
     * @param Cart $cart
     * @return float
     */
    public function getCartTotal(Cart $cart): float
    {
        return $cart->items->sum(fn($item) => $item->price * $item->quantity);
    }
}
