<?php

namespace App\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CategoryServiceInterface
 *
 * Defines the contract for category-related business operations.
 * This interface ensures that all category service implementations
 * follow a consistent API for CRUD operations.
 *
 * @package App\Contracts
 */
interface CategoryServiceInterface
{
    /**
     * Retrieve all categories.
     *
     * @return Collection<Category> A collection of all categories.
     */
    public function getAllCategories(): Collection;

    /**
     * Create a new category with the given data.
     *
     * @param array $data Validated category data.
     * @return Category The newly created category instance.
     */
    public function createCategory(array $data): Category;

    /**
     * Update an existing category with the provided data.
     *
     * @param Category $category The category to update.
     * @param array $data The validated data to update the category with.
     * @return Category The updated category instance.
     */
    public function updateCategory(Category $category, array $data): Category;

    /**
     * Delete the specified category.
     *
     * @param Category $category The category instance to delete.
     * @return bool True if deletion was successful, false otherwise.
     */
    public function deleteCategory(Category $category): bool;
}
