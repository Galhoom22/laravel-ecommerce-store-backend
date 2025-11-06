<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Contracts\ProductServiceInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Contracts\Repositories\CategoryRepositoryInterface;

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
    private ProductRepositoryInterface $productRepository;
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * ProductService constructor.
     *
     * @param ProductRepositoryInterface $productRepository The repository implementation for product data.
     */
    public function __construct(ProductRepositoryInterface $productRepository, CategoryRepositoryInterface $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
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
        $data = $this->handleImageUpload($data);
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
        $data = $this->handleImageUpload($data);
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

    /**
     * Handle product image upload and normalize the image path.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function handleImageUpload(array $data): array
    {
        // Early return if no image uploaded
        if (
            !isset($data['image']) ||
            !$data['image'] instanceof UploadedFile ||
            !isset($data['category_id'])
        ) {
            return $data;
        }

        $file = $data['image'];

        // Resolve dynamic storage path based on category hierarchy
        $category = $this->categoryRepository->findById((int) $data['category_id']);
        if (!$category) {
            return $data;
        }

        $path = $this->resolveCategoryImagePath($category);

        // Store image in the public disk under products/{category_path}
        $storedPath = $file->store($path, 'public');

        $data['image'] = $storedPath;

        return $data;
    }

    /**
     * Build the dynamic image path from category hierarchy.
     *
     * Example: products/electronics/mobiles
     *
     * @param Category $category
     * @return string
     */
    private function resolveCategoryImagePath(Category $category): string
    {
        $segments = [];

        // Traverse up the category tree if parent exists
        $current = $category;
        while ($current) {
            array_unshift($segments, Str::slug($current->name));
            $current = $current->parent;
        }

        return 'products/' . implode('/', $segments);
    }
}
