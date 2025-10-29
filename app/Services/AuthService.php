<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Contracts\AuthServiceInterface;

/**
 * Responsible for handling user authentication and registration logic.
 *
 * This service provides methods for:
 * - Registering new users.
 * - Attempting user login with credentials.
 * - Logging in a specific user instance.
 */
class AuthService implements AuthServiceInterface
{
    /**
     * Register a new user in the system.
     *
     * @param array $data The validated registration data (name, email, password).
     * @return User The newly created user instance.
     */
    public function registerUser(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Attempt to authenticate a user with provided credentials.
     *
     * @param array $credentials The login credentials (email, password).
     * @return bool Returns true if authentication succeeds false otherwise.
     */
    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Log in the specified user instance.
     *
     * @param User $user The user instance to be logged in.
     * @return void
     */
    public function loginUser(User $user): void
    {
        Auth::login($user);
    }

    /**
     * Log out the currently authenticated user.
     *
     * @return void
     */
    public function logoutUser(): void
    {
        Auth::logout();
    }
}
