<?php

namespace App\Services;

use App\Contracts\ProductServiceInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProductService
 *
 * Implements the business logic layer for Product operations.
 * Delegates data access responsibilities to the ProductRepository,
 * ensuring clear separation of concerns and compliance with SOLID principles.
 *
 * @package App\Services
 */
class ProductService implements ProductServiceInterface
{
    /**
     * Repository instance for handling product data access.
     *
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductRepositoryInterface $productRepository The repository implementation for product data.
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Retrieve paginated list of products.
     *
     * @param int $perPage Number of products per page.
     * @return LengthAwarePaginator Paginated product collection.
     */
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator
    {
        return $this->productRepository->getPaginated($perPage);
    }

    /**
     * Create a new product.
     *
     * @param array $data Validated product attributes.
     * @return Product The created product instance.
     */
    public function createProduct(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update an existing product.
     *
     * @param Product $product The product instance to update.
     * @param array $data The validated update data.
     * @return Product The updated product instance.
     */
    public function updateProduct(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    /**
     * Delete a product (soft delete).
     *
     * @param Product $product The product instance to delete.
     * @return bool True if deletion succeeded, false otherwise.
     */
    public function deleteProduct(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }
}
