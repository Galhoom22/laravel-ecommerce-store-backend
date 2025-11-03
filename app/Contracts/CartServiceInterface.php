<?php

namespace App\Contracts;

use App\Models\Cart;
use App\Models\CartItem;

/**
 * Interface CartServiceInterface
 *
 * Defines all business logic for cart operations.
 * Includes both guest (session-based) and user (database-based) carts,
 * as well as logic to merge guest cart into user cart upon login.
 */
interface CartServiceInterface
{
    // ============================================
    // Guest Cart (Session-based)
    // ============================================

    /**
     * Retrieve the guest cart stored in session.
     *
     * @return array
     */
    public function getGuestCart(): array;

    /**
     * Add a product to the guest cart in session.
     *
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function addToGuestCart(int $productId, int $quantity): void;

    /**
     * Update the quantity of a specific product in the guest cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function updateGuestCartItem(int $productId, int $quantity): void;

    /**
     * Remove a specific product from the guest cart.
     *
     * @param int $productId
     * @return void
     */
    public function removeFromGuestCart(int $productId): void;

    /**
     * Clear all items from the guest cart.
     *
     * @return void
     */
    public function clearGuestCart(): void;

    /**
     * Calculate the total price of all items in the guest cart.
     *
     * @return float
     */
    public function getGuestCartTotal(): float;

    // ============================================
    // User Cart (Database via Repository)
    // ============================================

    /**
     * Retrieve the authenticated user's cart from the database.
     *
     * @return Cart|null
     */
    public function getUserCart(): ?Cart;

    /**
     * Add a product to the authenticated user's cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return CartItem
     */
    public function addToUserCart(int $productId, int $quantity): CartItem;

    /**
     * Update the quantity of a product in the user's cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return CartItem
     */
    public function updateUserCartItem(int $productId, int $quantity): CartItem;

    /**
     * Remove a specific product from the user's cart.
     *
     * @param int $productId
     * @return bool
     */
    public function removeFromUserCart(int $productId): bool;

    // ============================================
    // Merge Logic
    // ============================================

    /**
     * Merge guest cart items from session into user's database cart upon login.
     *
     * @param int $userId
     * @return void
     */
    public function mergeGuestCartToUser(int $userId): void;
    /**
     * Get guest cart items with full product details.
     *
     * @return array<int, array{product: \App\Models\Product|null, quantity: int}>
     */
    public function getGuestCartWithProducts(): array;
}
