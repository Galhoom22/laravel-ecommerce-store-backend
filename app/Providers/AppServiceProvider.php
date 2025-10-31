<?php

namespace App\Providers;

use App\Models\Product;
use App\Services\AuthService;
use App\Policies\ProductPolicy;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Gate;
use App\Contracts\AuthServiceInterface;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CategoryServiceInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Policies\CategoryPolicy;

/**
 * Class AppServiceProvider
 *
 * Registers and bootstraps core application services.
 * Handles service container bindings for dependency injection,
 * ensuring loose coupling and SOLID-compliant architecture.
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * This method binds interfaces to their concrete implementations,
     * enabling Laravel's automatic dependency injection.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind authentication service
        $this->app->bind(
            AuthServiceInterface::class,
            AuthService::class
        );

        // Bind product service
        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class
        );

        // Bind product repository
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        // Bind category service
        $this->app->bind(
            CategoryServiceInterface::class,
            CategoryService::class
        );

        // Bind category repository
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * Used to define gates, policies, or any
     * global bootstrapping required by the application.
     *
     * @return void
     */
    public function boot(): void
    {
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
    }
}
