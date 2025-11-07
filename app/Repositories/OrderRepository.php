<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class OrderRepository
 *
 * Handles all database interactions related to Orders and OrderItems.
 * Implements OrderRepositoryInterface according to Repository Pattern.
 */
final class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Create a new order record.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Create a new order item for a given order.
     *
     * @param Order $order
     * @param array $data
     * @return OrderItem
     */
    public function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    /**
     * Get all orders for a specific user with related items and products.
     *
     * @param int $userId
     * @return Collection<int, Order>
     */
    public function getUserOrders(int $userId): Collection
    {
        return Order::where('user_id', $userId)
            ->with('items.product')
            ->latest()
            ->get();
    }

    /**
     * Find a specific order by ID with related items and products.
     *
     * @param int $id
     * @return Order|null
     */
    public function findById(int $id): ?Order
    {
        return Order::with('items.product')->find($id);
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Order::with(['user'])
            ->latest()
            ->paginate($perPage);
    }

    public function update(int $id, array $data): bool
    {
        $order = Order::findOrFail($id);
        return $order->update($data);
    }
}
