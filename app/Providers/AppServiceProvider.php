<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Product;
use App\Models\Category;
use App\Services\AuthService;
use App\Services\CartService;
use App\Services\OrderService;
use App\Policies\ProductPolicy;
use Illuminate\Support\Facades\Route;

// Services
use App\Policies\CategoryPolicy;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Repositories\CartRepository;
use Illuminate\Support\Facades\Gate;

// Service Interfaces
use App\Repositories\OrderRepository;
use App\Contracts\AuthServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;

// Repositories
use App\Contracts\OrderServiceInterface;
use App\Repositories\CategoryRepository;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CategoryServiceInterface;

// Repository Interfaces
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

/**
 * Class AppServiceProvider
 *
 * Registers and bootstraps core application services.
 * Handles service container bindings for dependency injection,
 * ensuring loose coupling and SOLID-compliant architecture.
 */
final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * Binds interfaces to their concrete implementations.
     *
     * @return void
     */
    public function register(): void
    {
        // -----------------------
        // Service Bindings
        // -----------------------
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        // -----------------------
        // Repository Bindings
        // -----------------------
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * Registers authorization policies and
     * performs global application bootstrapping.
     *
     * @return void
     */
    public function boot(): void
    {
        // -----------------------
        // Policy Registration
        // -----------------------
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);

        // -----------------------
        // Spatie Middleware Registration (Laravel 12)
        // -----------------------
        Route::aliasMiddleware('role', RoleMiddleware::class);
        Route::aliasMiddleware('permission', PermissionMiddleware::class);
        Route::aliasMiddleware('role_or_permission', RoleOrPermissionMiddleware::class);
    }
}
