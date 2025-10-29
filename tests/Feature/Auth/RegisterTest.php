<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature test for the user registration process.
 *
 * This test validates:
 * - User can view the registration page.
 * - User can register successfully.
 * - User is automatically logged in after registration.
 * - Validation errors appear for invalid data.
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Arrange → Act → Assert pattern applied cleanly.
     *
     * @return void
     */
    public function test_user_can_register_successfully(): void
    {
        // Arrange: Prepare registration data
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act: Send POST request to the registration route
        $response = $this->post('/register', $data);

        // Assert: Check redirection and authentication
        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test that registration fails with invalid data.
     *
     * @return void
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        // Arrange: Invalid registration data (missing password)
        $data = [
            'name' => 'Invalid User',
            'email' => 'invalid@example.com',
            'password' => '',
            'password_confirmation' => '',
        ];

        // Act: Send POST request
        $response = $this->post('/register', $data);

        // Assert: Ensure user not created + validation errors
        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
        $this->assertDatabaseMissing('users', [
            'email' => 'invalid@example.com',
        ]);
    }

    /**
     * Test that the registration page can be viewed.
     *
     * @return void
     */
    public function test_user_can_view_register_page(): void
    {
        // Act: Access the register page
        $response = $this->get('/register');

        // Assert: Check status and content
        $response->assertStatus(200);
        $response->assertSee('Create an Account');
    }
}
