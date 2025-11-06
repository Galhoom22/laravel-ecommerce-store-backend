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

    /**
     * Get the full URL of the product image or a default placeholder.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image === null || $this->image === '') {
            // fallback to a default "no image" asset
            return asset('assets/img/no-image-placeholder.png');
        }

        // If image is already a full URL (seeder uses external link)
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Otherwise, assume it is stored on the public disk
        return asset('storage/' . ltrim($this->image, '/'));
    }

    public function category(): BelongsTo
    {
        // A Product belongs to one Category (with a default fallback)
        return $this->belongsTo(Category::class)->withDefault([
            'name' => 'Uncategorized',
        ]);
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
