<?php

namespace App\Contracts\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * Find a category by its ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;
    /**
     * Retrieve all categories with parent relationship.
     *
     * @return Collection<Category>
     */
    public function getAll(): Collection;

    /**
     * Get paginated categories with parent relationship.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator;

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
