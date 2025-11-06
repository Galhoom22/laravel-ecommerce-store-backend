<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Cart;
use App\Models\CartItem;
use InvalidArgumentException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CartService
 *
 * Handles all cart business logic for both guests (session-based)
 * and authenticated users (database-backed).
 */
final class CartService implements CartServiceInterface
{
    protected CartRepositoryInterface $cartRepository;
    protected ProductRepositoryInterface $productRepository;

    private const GUEST_CART_SESSION_KEY = 'guest_cart';

    /**
     * CartService constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
    }

    // ======================================================
    // Guest Cart (session-based)
    // ======================================================

    /**
     * Get the current guest cart from session.
     *
     * @return array<int, array{product_id:int, quantity:int}>
     */
    public function getGuestCart(): array
    {
        return session(self::GUEST_CART_SESSION_KEY, []);
    }

    /**
     * Add a product to the guest cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function addToGuestCart(int $productId, int $quantity): void
    {
        $cart = $this->getGuestCart();

        foreach ($cart as &$item) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] += $quantity;
                session([self::GUEST_CART_SESSION_KEY => $cart]);
                return;
            }
        }

        $cart[] = ['product_id' => $productId, 'quantity' => $quantity];
        session([self::GUEST_CART_SESSION_KEY => $cart]);
    }

    /**
     * Update quantity for a specific item in guest cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function updateGuestCartItem(int $productId, int $quantity): void
    {
        $cart = array_map(function ($item) use ($productId, $quantity) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] = max(0, $quantity);
            }
            return $item;
        }, $this->getGuestCart());

        session([
            self::GUEST_CART_SESSION_KEY => array_values(
                array_filter($cart, fn($i) => $i['quantity'] > 0)
            ),
        ]);
    }

    /**
     * Remove an item from the guest cart.
     *
     * @param int $productId
     * @return void
     */
    public function removeFromGuestCart(int $productId): void
    {
        $cart = array_values(
            array_filter(
                $this->getGuestCart(),
                fn($item) => $item['product_id'] !== $productId
            )
        );

        session([self::GUEST_CART_SESSION_KEY => $cart]);
    }

    /**
     * Clear all items from guest cart.
     *
     * @return void
     */
    public function clearGuestCart(): void
    {
        session()->forget(self::GUEST_CART_SESSION_KEY);
    }

    /**
     * Calculate total price of guest cart.
     *
     * @return float
     */
    public function getGuestCartTotal(): float
    {
        return collect($this->getGuestCart())->sum(function ($item) {
            $product = $this->productRepository->findById($item['product_id']);
            return $product ? $product->price * $item['quantity'] : 0;
        });
    }

    /**
     * Get guest cart items with their full product details.
     *
     * @return array<int, array{product: \App\Models\Product|null, quantity: int}>
     */
    public function getGuestCartWithProducts(): array
    {
        return collect($this->getGuestCart())
            ->map(function ($item) {
                return [
                    'product' => $this->productRepository->findById($item['product_id']),
                    'quantity' => $item['quantity'],
                ];
            })
            ->toArray();
    }

    // ======================================================
    // User Cart (via Repository)
    // ======================================================

    /**
     * Get the authenticated user's cart.
     *
     * @return Cart|null
     */
    public function getUserCart(): ?Cart
    {
        /** @var int|null $userId */
        $userId = Auth::id();
        return $userId ? $this->cartRepository->findByUserId($userId) : null;
    }

    /**
     * Add or update a product in the authenticated user's cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return \App\Models\CartItem
     */
    public function addToUserCart(int $productId, int $quantity): CartItem
    {
        $userId = Auth::id() ?? throw new InvalidArgumentException('User not authenticated.');

        // Get or create user cart
        $cart = $this->cartRepository->findByUserId($userId)
            ?? $this->cartRepository->createForUser($userId);

        // Find product
        $product = $this->productRepository->findById($productId)
            ?? throw new InvalidArgumentException('Product not found.');

        // Check if this product already exists in user's cart
        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            // If product exists, increment quantity instead of inserting duplicate
            $existingItem->increment('quantity', $quantity);
            return $existingItem->refresh();
        }

        // Otherwise create a new cart item
        return $this->cartRepository->addItem(
            $cart,
            $productId,
            $quantity,
            (float) $product->price
        );
    }

    /**
     * Update quantity for a specific item in the user's cart.
     *
     * @param int $productId
     * @param int $quantity
     * @return CartItem
     */
    public function updateUserCartItem(int $productId, int $quantity): CartItem
    {
        /** @var int|null $userId */
        $userId = Auth::id() ?? throw new InvalidArgumentException('User not authenticated.');
        $cart = $this->cartRepository->findByUserId($userId)
            ?? throw new InvalidArgumentException('Cart not found.');

        $item = $cart->items->firstWhere('product_id', $productId)
            ?? throw new InvalidArgumentException('Cart item not found.');

        return $this->cartRepository->updateItemQuantity($item, $quantity);
    }

    /**
     * Remove a product from user's cart.
     *
     * @param int $productId
     * @return bool
     */
    public function removeFromUserCart(int $productId): bool
    {
        /** @var int|null $userId */
        $userId = Auth::id();
        if (!$userId) {
            return false;
        }

        $cart = $this->cartRepository->findByUserId($userId);
        $item = $cart?->items->firstWhere('product_id', $productId);

        return $item ? $this->cartRepository->removeItem($item) : false;
    }

    // ======================================================
    // Merge Guest → User
    // ======================================================

    /**
     * Merge guest cart into authenticated user's cart upon login.
     *
     * @param int $userId
     * @return void
     */
    public function mergeGuestCartToUser(int $userId): void
    {
        $guestCart = $this->getGuestCart();

        if (empty($guestCart)) {
            return;
        }

        $cart = $this->cartRepository->findByUserId($userId)
            ?? $this->cartRepository->createForUser($userId);

        foreach ($guestCart as $guestItem) {
            $productId = (int) $guestItem['product_id'];
            $quantity  = (int) $guestItem['quantity'];

            $product = $this->productRepository->findById($productId);
            if (!$product) {
                continue;
            }

            $existingItem = $cart->items->firstWhere('product_id', $productId);

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $quantity;
                $this->cartRepository->updateItemQuantity($existingItem, $newQuantity);
            } else {
                $this->cartRepository->addItem(
                    $cart,
                    $productId,
                    $quantity,
                    (float) $product->price
                );
            }
        }

        $this->clearGuestCart();
    }
}
