<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Single Responsibility: Represents a product category with hierarchical structure support
 */
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}
