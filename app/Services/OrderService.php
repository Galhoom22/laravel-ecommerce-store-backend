<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\OrderServiceInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\CartServiceInterface;
use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Throwable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class OrderService
 *
 * Handles all order-related business logic.
 * Converts carts into orders and provides user order retrieval.
 */
final class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly CartServiceInterface $cartService
    ) {} // constructor property promotion

    /**
     * Place a new order for the given user.
     *
     * @param int $userId
     * @param array $shippingData
     * @return Order
     * @throws Throwable
     */
    public function placeOrder(int $userId, array $shippingData): Order
    {
        $cart = $this->cartService->getUserCart();

        if (!$cart || $cart->items->isEmpty()) {
            throw new InvalidArgumentException('Cannot place order with empty cart.');
        }

        $total = $cart->items->sum(fn($item) => $item->quantity * $item->price);

        return DB::transaction(function () use ($userId, $shippingData, $cart, $total) {
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'total_price' => $total,
                'status' => 'pending',
                'shipping_address' => $shippingData['address'],
                'shipping_city' => $shippingData['city'],
                'shipping_phone' => $shippingData['phone'],
            ]);

            foreach ($cart->items as $item) {
                $this->orderRepository->createOrderItem($order, [
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                ]);
            }

            $this->cartRepository->clearCart($cart);

            return $order;
        });
    }

    /**
     * Get all orders for a specific user.
     *
     * @param int $userId
     * @return Collection<int, Order>
     */
    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->getUserOrders($userId);
    }

    /**
     * Get a specific order by ID for a given user.
     *
     * @param int $orderId
     * @param int $userId
     * @return Order|null
     */
    public function getOrderById(int $orderId, int $userId): ?Order
    {
        $order = $this->orderRepository->findById($orderId);

        return ($order && $order->user_id === $userId)
            ? $order
            : null;
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return $this->orderRepository->getPaginated($perPage);
    }

    public function findById(int $id): ?Order
    {
        return $this->orderRepository->findById($id);
    }
}
