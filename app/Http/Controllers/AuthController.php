<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Contracts\AuthServiceInterface;
use Illuminate\Http\Request;

/**
 * Controller responsible for handling user authentication actions.
 *
 * Handles user registration and login requests by delegating
 * business logic to the AuthService layer.
 */
class AuthController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var AuthServiceInterface
     */
    protected AuthServiceInterface $authService;

    /**
     * Inject the authentication service dependency.
     *
     * @param AuthServiceInterface $authService The authentication service implementation.
     */
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle user registration and automatic login.
     *
     * @param RegisterRequest $request The validated registration request.
     * @return \Illuminate\Http\RedirectResponse Redirects the user to the home route.
     */
    public function register(RegisterRequest $request)
    {
        // 1. Delegate user creation to AuthService
        $user = $this->authService->registerUser($request->validated());

        // 2. Auto-login the user via AuthService
        $this->authService->loginUser($user);

        // 3. Redirect to home
        return redirect()->route('home');
    }

    /**
     * Handle user login attempt.
     *
     * @param LoginRequest $request The validated login request.
     * @return \Illuminate\Http\RedirectResponse Redirects on success or failure with error messages.
     */
    public function login(LoginRequest $request)
    {
        // 1. Validate the request data
        $credentials = $request->validated();

        // 2. Attempt to authenticate the user
        if ($this->authService->attemptLogin($credentials)) {
            // 3. Regenerate the session ID after successful login
            $request->session()->regenerate();

            // 4. Retrieve the authenticated user as concrete User model
            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            // 5. Redirect based on role (safe check to avoid errors if trait is not available)
            if ($user !== null && method_exists($user, 'hasRole') && $user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            }

            // 6. Default redirect for normal customers
            return redirect()->route('home');
        }

        // 7. If authentication fails, redirect back with error message
        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email'));
    }

    /**
     * Log out the authenticated user and destroy the session.
     *
     * @param Request $request The current HTTP request instance.
     * @return \Illuminate\Http\RedirectResponse Redirects the user to the home page.
     */
    public function logout(Request $request)
    {
        // 1. Delegate logout to AuthService (business logic)
        $this->authService->logoutUser();

        // 2. Invalidate the session
        $request->session()->invalidate();

        // 3. Regenerate CSRF token for Security
        $request->session()->regenerateToken();

        // 4. Redirect to home route
        return redirect()->route('home');
    }
}
