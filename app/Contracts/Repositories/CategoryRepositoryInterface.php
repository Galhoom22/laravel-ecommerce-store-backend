<?php

namespace App\Contracts\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface CategoryRepositoryInterface
 *
 * Defines data access contract for Category entity.
 * Handles all direct database operations for categories.
 *
 * @package App\Contracts\Repositories
 */
interface CategoryRepositoryInterface
{
    /**
     * Retrieve all categories with parent relationship.
     *
     * @return Collection<Category>
     */
    public function getAll(): Collection;

    /**
     * Persist a new category to the database.
     *
     * @param array $data Category attributes.
     * @return Category
     */
    public function create(array $data): Category;

    /**
     * Update an existing category in the database.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data): Category;

    /**
     * Soft delete a category from the database.
     *
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category): bool;
}
