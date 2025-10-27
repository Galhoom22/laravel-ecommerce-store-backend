<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. create 20 products using the factory
        Product::factory()->count(20)->create();

        // 2. find specific catgories
        $phonesCategory = Category::where('slug', 'phones')->first();
        $laptopsCategory = Category::where('slug', 'laptops')->first();
        $booksCategory = Category::where('slug', 'books')->first();

        // 3. create specific products

        // check if category exists before creating products to avoid errors
        if ($phonesCategory) {
            Product::factory()->create([
                'name' => 'iPhone 15 Pro',
                'slug' => Str::slug('iPhone 15 Pro'),
                'description' => 'The latest flagship iPhone with Pro features.',
                'price' => 1099.99,
                'quantity' => 50,
                'category_id' => $phonesCategory->id,
            ]);
        }

        if ($laptopsCategory) {
            Product::factory()->create([
                'name' => 'MacBook Air M3', // Updated model
                'slug' => Str::slug('MacBook Air M3'),
                'description' => 'Apple\'s thin and light laptop with the M3 chip.',
                'price' => 999.00,
                'quantity' => 30,
                'category_id' => $laptopsCategory->id,
            ]);
        }

        if ($booksCategory) {
            Product::factory()->create([
                'name' => 'The Catcher in the Rye',
                'slug' => Str::slug('The Catcher in the Rye'),
                'description' => 'A classic novel by J.D. Salinger.',
                'price' => 19.99,
                'quantity' => 100,
                'category_id' => $booksCategory->id,
            ]);
        }
    }
}
