<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase; // reset the database before each test

    /**
     * test the admin role has admin permissions
     */
    public function test_admin_user_has_admin_permissions(): void
    {
        // Arrange
        // 1. run the RolePermissionSeeder to create roles/permissions
        $this->seed(RolePermissionSeeder::class);

        // 2. create a new user using factory
        $user = User::factory()->create();

        // Act
        // 3. assign the admin role
        $user->assignRole('admin');

        // Assert
        // 4. assert the user now has a permission that admins should have
        $this->assertTrue($user->hasPermissionTo('manage-products'));
        $this->assertTrue($user->hasPermissionTo('manage-categories'));
    }

    /**
     * test the customer role has only customer permission
     */
    public function test_customer_user_has_only_customer_permissions(): void
    {
        // Arrange
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();

        // Act
        $user->assignRole('customer');

        // Assert
        // Assert the user has place-orders
        $this->assertTrue($user->hasPermissionTo('place-orders'));

        // Assert the user does not have admin permissions
        $this->assertFalse($user->hasPermissionTo('manage-products'));
    }
}
