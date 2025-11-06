<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * Get all categories (non-paginated) with parent relationship.
     *
     * @return Collection<Category>
     */
    public function getAll(): Collection
    {
        return Category::with('parent')
            ->orderBy('id', 'asc')
            ->get();
    }

    /**
     * Get paginated categories with parent relationship.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Category::with('parent')->orderByDesc('id')->paginate($perPage);
    }

    /**
     * Create a new category in the database.
     *
     * @param array<string, mixed> $data
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
     * @param array<string, mixed> $data
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
