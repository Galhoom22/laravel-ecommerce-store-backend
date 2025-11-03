<?php

declare(strict_types=1);

namespace App\Contracts\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Collection;

/**
 * Interface OrderRepositoryInterface
 *
 * Defines the contract for managing orders and their related items.
 */
interface OrderRepositoryInterface
{
    /**
     * Create a new order record.
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;

    /**
     * Create a new order item for a given order.
     *
     * @param Order $order
     * @param array $data
     * @return OrderItem
     */
    public function createOrderItem(Order $order, array $data): OrderItem;

    /**
     * Retrieve all orders for a specific user with their items and products.
     *
     * @param int $userId
     * @return Collection<int, Order>
     */
    public function getUserOrders(int $userId): Collection;

    /**
     * Find a specific order by its ID with related items and products.
     *
     * @param int $id
     * @return Order|null
     */
    public function findById(int $id): ?Order;
}
