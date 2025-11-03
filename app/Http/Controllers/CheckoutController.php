<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contracts\CartServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

/**
 * Class CheckoutController
 *
 * Handles the checkout process — displaying the form and placing the order.
 */
final class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartServiceInterface $cartService,
        private readonly OrderServiceInterface $orderService
    ) {} // constructor property promotion

    /**
     * Display the checkout page.
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to continue.');
        }

        $cart = $this->cartService->getUserCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cart->items->sum(fn($item) => $item->quantity * $item->price);

        return view('checkout.index', [
            'cart'  => $cart,
            'items' => $cart->items,
            'total' => $total,
        ]);
    }

    /**
     * Handle the order placement request.
     *
     * @param PlaceOrderRequest $request
     * @return RedirectResponse
     */
    public function store(PlaceOrderRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $order = $this->orderService->placeOrder(Auth::id(), $validated);

            return redirect()
                ->route('orders.show', $order->id)
                ->with('success', 'Your order has been placed successfully!');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Something went wrong while placing your order.');
        }
    }
}
