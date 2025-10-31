<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface ProductServiceInterface
 *
 * Defines the contract for product-related business operations.
 * This interface ensures a consistent API for product CRUD functionality
 * and supports pagination for product listings.
 *
 * @package App\Contracts
 */
interface ProductServiceInterface
{
    /**
     * Retrieve all products with pagination.
     *
     * @param int $perPage Number of products to display per page. Default is 15.
     * @return LengthAwarePaginator Paginated list of products.
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator;

    /**
     * Create a new product with the given data.
     *
     * @param array $data Validated product data.
     * @return Product The newly created product instance.
     */
    public function createProduct(array $data): Product;

    /**
     * Update an existing product with the provided data.
     *
     * @param Product $product The product to update.
     * @param array $data The validated data to update the product with.
     * @return Product The updated product instance.
     */
    public function updateProduct(Product $product, array $data): Product;

    /**
     * Delete the specified product.
     *
     * @param Product $product The product instance to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function deleteProduct(Product $product): bool;
}
