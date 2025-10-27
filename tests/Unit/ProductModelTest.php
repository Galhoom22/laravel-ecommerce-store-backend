<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;
    /**
     * test that a product belongs to a category.
     */
    public function test_product_belongs_to_a_category(): void
    {
        // Arrange: create a category and a product linked to it
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        // Act: retrieve the category via the relationship
        $retrievedCategory = $product->category;

        // Assert: check the relationship works
        $this->assertInstanceOf(Category::class, $retrievedCategory);
        $this->assertEquals($category->id, $retrievedCategory->id);
    }

    /**
     * test that the search scope finds products by name
     */
    public function test_search_scope_finds_product_by_name(): void
    {
        // Arrange:
        // 1. create a category first
        $category = Category::factory()->create();

        // 2. create products and assign the category_id
        Product::factory()->create([
            'name' => 'iPhone 13',
            'category_id' => $category->id
        ]);
        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'category_id' => $category->id
        ]);

        // Act: use the search scope to find the product by name
        $results = Product::search('iPhone')->get();

        // Assert: check the search scope works
        $this->assertCount(1, $results);
        $this->assertEquals('iPhone 13', $results->first()->name);
    }
}
