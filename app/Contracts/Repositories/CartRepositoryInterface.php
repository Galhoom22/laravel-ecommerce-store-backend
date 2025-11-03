<?php

namespace App\Contracts\Repositories;

use App\Models\Cart;
use App\Models\CartItem;

/**
 * Interface CartRepositoryInterface
 *
 * Defines all data-access operations related to Cart and CartItems.
 * This interface separates persistence logic from business logic (Repository Pattern).
 */
interface CartRepositoryInterface
{
    /**
     * Find the user's cart by user ID.
     *
     * @param int $userId
     * @return Cart|null  Returns the Cart instance or null if not found.
     */
    public function findByUserId(int $userId): ?Cart;

    /**
     * Create a new cart for the given user.
     *
     * @param int $userId
     * @return Cart
     */
    public function createForUser(int $userId): Cart;

    /**
     * Add a new item to the cart.
     *
     * @param Cart   $cart
     * @param int    $productId
     * @param int    $quantity
     * @param float  $price
     * @return CartItem
     */
    public function addItem(Cart $cart, int $productId, int $quantity, float $price): CartItem;

    /**
     * Update the quantity of an existing cart item.
     *
     * @param CartItem $item
     * @param int $quantity
     * @return CartItem
     */
    public function updateItemQuantity(CartItem $item, int $quantity): CartItem;

    /**
     * Remove a specific cart item.
     *
     * @param CartItem $item
     * @return bool  Returns true if deletion succeeded.
     */
    public function removeItem(CartItem $item): bool;

    /**
     * Clear all items from the given cart.
     *
     * @param Cart $cart
     * @return bool  Returns true if all items were deleted.
     */
    public function clearCart(Cart $cart): bool;

    /**
     * Calculate the total value of the cart.
     *
     * @param Cart $cart
     * @return float
     */
    public function getCartTotal(Cart $cart): float;
}
