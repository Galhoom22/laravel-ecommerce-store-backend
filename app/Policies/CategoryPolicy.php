<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

/**
 * Class CategoryPolicy
 *
 * Defines authorization logic for category management operations.
 * Ensures that only users with the 'admin' role can perform CRUD operations on categories.
 *
 * @package App\Policies
 */
class CategoryPolicy
{
    /**
     * Determine whether the user can view any categories.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view the category.
     *
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function view(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create categories.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the category.
     *
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function update(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the category.
     *
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the category.
     *
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function restore(User $user, Category $category): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the category.
     *
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function forceDelete(User $user, Category $category): bool
    {
        return false;
    }
}
