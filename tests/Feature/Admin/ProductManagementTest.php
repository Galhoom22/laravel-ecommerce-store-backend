<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

/**
 * Class ProductManagementTest
 *
 * This test suite verifies product management functionality in the admin panel,
 * ensuring that only authorized users (admins) can access and manage products.
 */
class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure 'admin' role exists for tests
        Role::create(['name' => 'admin']);
    }
    /**
     * Test that an admin user can view the product index page successfully.
     *
     * @return void
     */
    public function test_admin_can_view_products_index(): void
    {
        // Arrange: Create admin user and sample products
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $category = Category::factory()->create();
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        // Act: Access products index as admin
        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        // Assert: Page loads successfully
        $response->assertStatus(200);
        $response->assertSee('Manage Products');
    }

    /**
     * Test that an admin can create a new product successfully.
     *
     * @return void
     */
    public function test_admin_can_create_product(): void
    {
        // Arrange
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $category = Category::factory()->create();

        $productData = [
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test description',
            'price' => 99.99,
            'quantity' => 10,
            'category_id' => $category->id,
            'is_active' => true,
        ];

        // Act: Submit POST request to create a product
        $response = $this->actingAs($admin)->post(route('admin.products.store'), $productData);

        // Assert: Redirect to index and product exists in DB
        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', ['slug' => 'test-product']);
    }

    /**
     * Test that a non-admin (regular user) cannot access admin product routes.
     *
     * @return void
     */
    public function test_non_admin_cannot_access_products(): void
    {
        // Arrange: Create normal user without admin role
        $user = User::factory()->create();

        // Act: Try to access admin route
        $response = $this->actingAs($user)->get(route('admin.products.index'));

        // Assert: Should be forbidden (HTTP 403)
        $response->assertStatus(403);
    }
}
