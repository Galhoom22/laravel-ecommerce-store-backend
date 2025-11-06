<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Throwable;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AddToCartRequest;
use App\Contracts\CartServiceInterface;
use App\Http\Requests\UpdateCartRequest;
use App\Contracts\Repositories\CartRepositoryInterface;

/**
 * Class CartController
 *
 * Handles all cart operations for both guests and authenticated users.
 */
final class CartController extends Controller
{
    protected CartServiceInterface $cartService;
    protected CartRepositoryInterface $cartRepository;

    /**
     * CartController constructor.
     *
     * @param CartServiceInterface $cartService
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        CartServiceInterface $cartService,
        CartRepositoryInterface $cartRepository
    ) {
        $this->cartService = $cartService;
        $this->cartRepository = $cartRepository;
    }

    // ======================================================
    // 1. INDEX
    // ======================================================

    /**
     * Display the current cart (guest or user).
     *
     * @return View
     */
    public function index(): View
    {
        try {
            if (Auth::check()) {
                // Authenticated user cart
                $cart = $this->cartService->getUserCart();
                $items = $cart?->items()->with('product')->get() ?? collect();
                $total = $items->sum(fn($item) => $item->quantity * $item->product->price);
            } else {
                // Guest cart
                $guestItems = $this->cartService->getGuestCartWithProducts();
                $items = collect($guestItems)->map(function ($item) {
                    return (object) [
                        'product' => $item['product'],
                        'quantity' => $item['quantity'],
                    ];
                });
                $total = $this->cartService->getGuestCartTotal();
                $cart = null;
            }

            return view('cart.index', compact('cart', 'items', 'total'));
        } catch (Throwable $e) {
            return view('cart.index', [
                'cart' => null,
                'items' => collect(),
                'total' => 0.0,
            ])->with('error', 'Unable to load cart. Please try again.');
        }
    }

    // ======================================================
    // 2. STORE
    // ======================================================

    /**
     * Add a product to the cart.
     *
     * @param AddToCartRequest $request
     * @return RedirectResponse
     */
    public function store(AddToCartRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $productId = (int) $validated['product_id'];
            $quantity  = (int) $validated['quantity'];

            if (Auth::check()) {
                $this->cartService->addToUserCart($productId, $quantity);
            } else {
                $this->cartService->addToGuestCart($productId, $quantity);
            }

            return redirect()->route('cart.index')->with('success', 'Product added to cart!');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to add product: ' . $e->getMessage());
        }
    }

    // ======================================================
    // 3. UPDATE
    // ======================================================

    /**
     * Update quantity for a product in the cart.
     *
     * @param UpdateCartRequest $request
     * @param int $productId
     * @return RedirectResponse
     */
    public function update(UpdateCartRequest $request, int $productId): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $quantity = (int) $validated['quantity'];

            if (Auth::check()) {
                $this->cartService->updateUserCartItem($productId, $quantity);
            } else {
                $this->cartService->updateGuestCartItem($productId, $quantity);
            }

            return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to update cart: ' . $e->getMessage());
        }
    }


    // ======================================================
    // 4. DESTROY
    // ======================================================

    /**
     * Remove a product from the cart.
     *
     * @param int $productId
     * @return RedirectResponse
     */
    public function destroy(int $productId): RedirectResponse
    {
        try {
            if (Auth::check()) {
                $this->cartService->removeFromUserCart($productId);
            } else {
                $this->cartService->removeFromGuestCart($productId);
            }

            return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to remove product: ' . $e->getMessage());
        }
    }

    // ======================================================
    // 5. CLEAR
    // ======================================================

    /**
     * Clear all items from the cart.
     *
     * @return RedirectResponse
     */
    public function clear(): RedirectResponse
    {
        try {
            if (Auth::check()) {
                $cart = $this->cartService->getUserCart();
                if ($cart) {
                    $this->cartRepository->clearCart($cart);
                }
            } else {
                $this->cartService->clearGuestCart();
            }

            return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
        } catch (Throwable $e) {
            return back()->with('error', 'Failed to clear cart: ' . $e->getMessage());
        }
    }
}
