<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ProductRepository
 *
 * Concrete implementation of ProductRepositoryInterface.
 * Handles all Product database operations using Eloquent ORM.
 *
 * @package App\Repositories
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get paginated products with category relationship.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Product::with('category')->paginate($perPage);
    }

    /**
     * Create a new product in the database.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update product in the database.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    /**
     * Soft delete a product.
     *
     * @param Product $product
     * @return bool
     */
    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    /**
     * Find a product by its ID.
     *
     * @param int $id
     * @return \App\Models\Product|null
     */
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }
}
