<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\CategoryServiceInterface;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class CategoryController
 *
 * Handles HTTP requests for category management in the admin panel.
 * Delegates business logic to CategoryService and enforces authorization via CategoryPolicy.
 *
 * @package App\Http\Controllers\Admin
 */
class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * The category service instance.
     *
     * @var CategoryServiceInterface
     */
    protected CategoryServiceInterface $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryServiceInterface $categoryService
     */
    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = $this->categoryService->getPaginatedCategories(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        // Use service instead of direct Model access
        $categories = $this->categoryService->getAllCategories();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $this->categoryService->createCategory($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param Category $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        // Use service instead of direct Model access
        $categories = $this->categoryService->getAllCategories();

        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $this->categoryService->deleteCategory($category);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
