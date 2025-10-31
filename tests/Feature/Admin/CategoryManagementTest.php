<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

/**
 * Class CategoryManagementTest
 *
 * Test suite for category management functionality in the admin panel.
 * Verifies CRUD operations and authorization enforcement using the Repository Pattern.
 *
 * @package Tests\Feature\Admin
 */
class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin user can view the category index page successfully.
     *
     * @return void
     */
    public function test_admin_can_view_categories_index(): void
    {
        // Arrange: Create the role first
        Role::create(['name' => 'admin']);

        // Create admin user and sample categories
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        Category::factory()->count(3)->create();

        // Act: Access categories index as admin
        $response = $this->actingAs($admin)->get(route('admin.categories.index'));

        // Assert: Page loads successfully
        $response->assertStatus(200);
        $response->assertSee('Manage Categories');
    }

    /**
     * Test that an admin can create a new category successfully.
     *
     * @return void
     */
    public function test_admin_can_create_category(): void
    {
        // Arrange
        Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $categoryData = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
            'parent_id' => null,
            'is_active' => true,
        ];

        // Act: Submit POST request to create a category
        $response = $this->actingAs($admin)->post(route('admin.categories.store'), $categoryData);

        // Assert: Redirect to index and category exists in DB
        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['slug' => 'test-category']);
    }

    /**
     * Test that a non-admin (regular user) cannot access admin category routes.
     *
     * @return void
     */
    public function test_non_admin_cannot_access_categories(): void
    {
        // Arrange: Ensure admin role exists
        Role::create(['name' => 'admin']);

        // Create normal user without admin role
        $user = User::factory()->create();

        // Act: Try to access admin route
        $response = $this->actingAs($user)->get(route('admin.categories.index'));

        // Assert: Should be forbidden (HTTP 403)
        $response->assertStatus(403);
    }
}
