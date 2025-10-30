<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductServiceInterface
{
    public function getAllProducts(int $perPage = 15): LengthAwarePaginator;

    public function createProduct(array $data): Product;

    public function updateProduct(Product $product, array $data): Product;

    public function deleteProduct(Product $product): bool;
}
