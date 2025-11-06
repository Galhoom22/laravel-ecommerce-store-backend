<?php

namespace App\Services;

use App\Contracts\CategoryServiceInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


/**
 * Class CategoryService
 *
 * Implements the business logic layer for Category operations.
 * Delegates data access responsibilities to the CategoryRepository.
 *
 * @package App\Services
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Repository instance for handling category data access.
     *
     * @var CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Retrieve all categories.
     *
     * @return Collection<Category>
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * Retrieve paginated categories list.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedCategories(int $perPage = 10): LengthAwarePaginator
    {
        return $this->categoryRepository->getPaginated($perPage);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return $this->categoryRepository->create($data);
    }

    /**
     * Update an existing category.
     *
     * @param Category $category
     * @param array $data
     * @return Category
     */
    public function updateCategory(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, $data);
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category): bool
    {
        return $this->categoryRepository->delete($category);
    }
}
