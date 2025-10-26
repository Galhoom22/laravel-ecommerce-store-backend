<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_category_can_have_children(): void
    {
        // Arrange
        $parent = Category::factory()->create();
        $child1 = Category::factory()->create(['parent_id' => $parent->id]);
        $child2 = Category::factory()->create(['parent_id' => $parent->id]);

        // Act
        $children = $parent->children;

        // Assert
        $this->assertInstanceOf(Collection::class, $children);
        $this->assertCount(2, $children);
        $this->assertTrue($children->contains($child1));
        $this->assertTrue($children->contains($child2));
    }

    public function test_child_category_belongs_to_parent(): void
    {
        // Arrange
        $parent = Category::factory()->create(['name' => 'Electronics']);
        $child = Category::factory()->create([
            'name' => 'Phones',
            'parent_id' => $parent->id
        ]);

        // Act
        $retrievedParent = $child->parent;

        // Assert
        $this->assertInstanceOf(Category::class, $retrievedParent);
        $this->assertEquals($parent->id, $retrievedParent->id);
        $this->assertEquals('Electronics', $retrievedParent->name);
    }
}
