<?php

namespace App\Contracts;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Register a new user in the system.
     *
     * @param array $data The validated registration data (name, email, password).
     * @return User The new created user instance.
     */
    public function registerUser(array $data): User;

    /**
     * Attempt to authenticate a user with the provided credentials.
     *
     * @param array $credentials The login credentials (email, password).
     * @return bool Returns true if authentication was successful, false otherwise.
     */
    public function attemptLogin(array $credentials): bool;

    /**
    * Log in the specified user.
    *
    * @param User $user The user instance to be logged in.
    * @return void
    */
    public function loginUser(User $user): void;

    /**
     * Log out the currently authenticated user.
     *
     * @return void
     */
    public function logoutUser():void;
}
