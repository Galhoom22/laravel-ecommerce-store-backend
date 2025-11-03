<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrderItem;

/**
 * Single Responsibility: Represents a product available in the store
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'image',
        'category_id',
        'is_active',
    ];

    protected $casts = [
        'price' => 'float:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        // A Product belongs to one Category
        return $this->belongsTo(Category::class);
    }

    /**
     * Summary of scopeSearch
     * scope a query to only include products matching a search term
     * searches within the product's name and description
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $term
     * @return void
     */
    public function scopeSearch(Builder $query, string $term): void
    {
        $query->where(function (Builder $subQuery) use ($term) {
            $subQuery->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
