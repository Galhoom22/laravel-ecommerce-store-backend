<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_admin_can_change_order_status_from_pending_to_processing(): void
    {
        // Arrange
        Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $order = Order::factory()->create(['status' => 'pending']);

        // Act
        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.orders.update', $order->id), [
                'status' => 'processing',
            ]);

        // Assert
        $response->assertRedirect(route('admin.orders.show', $order->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }

    public function test_admin_can_change_order_status_from_processing_to_completed(): void
    {
        // Arrange
        Role::create(['name' => 'admin']);

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $order = Order::factory()->create(['status' => 'processing']);

        // Act
        $response = $this
            ->actingAs($admin)
            ->patch(route('admin.orders.update', $order->id), [
                'status' => 'completed',
            ]);

        // Assert
        $response->assertRedirect(route('admin.orders.show', $order->id));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_non_admin_cannot_update_order_status(): void
    {
        // Arrange
        Role::create(['name' => 'user']);

        $user = User::factory()->create();
        $user->assignRole('user');

        $order = Order::factory()->create(['status' => 'pending']);

        // Act
        $response = $this
            ->actingAs($user)
            ->patch(route('admin.orders.update', $order->id), [
                'status' => 'processing',
            ]);

        // Assert
        $response->assertForbidden(); // or assertStatus(403)
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);
    }
}
