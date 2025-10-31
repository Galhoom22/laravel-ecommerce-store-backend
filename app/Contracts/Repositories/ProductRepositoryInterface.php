<?php

namespace App\Contracts\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ProductRepositoryInterface
 *
 * Defines data access contract for Product entity.
 * Handles all direct database operations for products.
 *
 * @package App\Contracts\Repositories
 */
interface ProductRepositoryInterface
{
    /**
     * Get paginated products with eager-loaded relationships.
     *
     * @param int $perPage Number of items per page.
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Persist a new product to the database.
     *
     * @param array $data Product attributes.
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update an existing product in the database.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data): Product;

    /**
     * Soft delete a product from the database.
     *
     * @param Product $product
     * @return bool
     */
    public function delete(Product $product): bool;
}
