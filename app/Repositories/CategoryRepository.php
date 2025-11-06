<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class CategoryRepository
 *
 * Concrete implementation of CategoryRepositoryInterface.
 * Handles all Category database operations using Eloquent ORM.
 *
 * @package App\Repositories
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return Category::find($id);
    }
    /**
     * Get all categories with parent relationship.
     *
     * @return Collection<Category>
     */
    public function getAll(): Collection
    {
        return Category::with('parent')->get();
    }

    /**
     * Create a new category in the database.
     *
     * @param array $data
     * @return Category
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update category in the database.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Soft delete a category.
     *
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category): bool
    {
        return $category->delete();
    }
}
