<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Support\Collection;
use Throwable;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface OrderServiceInterface
 *
 * Defines the contract for handling all order-related business logic,
 * including placing orders, retrieving user orders, and specific order access.
 */
interface OrderServiceInterface
{
    /**
     * Place a new order for the given user.
     *
     * Converts the user's cart into an order with items.
     *
     * @param int $userId
     * @param array $shippingData
     * @return Order
     *
     * @throws Throwable If cart is empty or transaction fails.
     */
    public function placeOrder(int $userId, array $shippingData): Order;

    /**
     * Get all orders belonging to a specific user.
     *
     * @param int $userId
     * @return Collection<int, Order>
     */
    public function getUserOrders(int $userId): Collection;

    /**
     * Get a specific order by ID for a given user.
     *
     * Includes an authorization check to ensure the order belongs to the user.
     *
     * @param int $orderId
     * @param int $userId
     * @return Order|null
     */
    public function getOrderById(int $orderId, int $userId): ?Order;

    /**
     * Retrieve paginated list of all orders for admin panel.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?Order;
}
