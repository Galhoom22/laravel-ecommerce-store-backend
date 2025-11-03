<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use App\Models\Category;
use App\Policies\ProductPolicy;
use App\Policies\CategoryPolicy;

// Services
use App\Services\AuthService;
use App\Services\CartService;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\OrderService;

// Service Interfaces
use App\Contracts\AuthServiceInterface;
use App\Contracts\CartServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CategoryServiceInterface;
use App\Contracts\OrderServiceInterface;

// Repositories
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

// Repository Interfaces
use App\Contracts\Repositories\CartRepositoryInterface;
use App\Contracts\Repositories\OrderRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;

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
    }
}
