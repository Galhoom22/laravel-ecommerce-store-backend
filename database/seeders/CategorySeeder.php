<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. create parent categories
        $electronics = Category::factory()->create([
            'name' => 'Electronics',
            'slug' => Str::slug('Electronics'),
        ]);

        Category::factory()->create([
            'name' => 'Clothing',
            'slug' => Str::slug('Clothing'),
        ]);

        Category::factory()->create([
            'name' => 'Books',
            'slug' => Str::slug('Books'),
        ]);

        // 2. create child categories under Electronics
        Category::factory()->create([
            'name' => 'Phones',
            'slug' => Str::slug('Phones'),
            'parent_id' => $electronics->id,
        ]);

        Category::factory()->create([
            'name' => 'Laptops',
            'slug' => Str::slug('Laptops'),
            'parent_id' => $electronics->id,
        ]);
    }
}
