<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ========== RELATIONSHIPS ==========

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(MarketplaceProduct::class, 'category_id');
    }

    // ========== SCOPES ==========

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    // ========== ACCESSORS ==========

    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    public function getProductCountAttribute(): int
    {
        return $this->products()->count();
    }

    // ========== STATIC METHODS ==========

    /**
     * Get categories as nested tree
     */
    public static function getTree(): \Illuminate\Support\Collection
    {
        $categories = self::active()->ordered()->get();

        return $categories->where('parent_id', null)->map(function ($category) use ($categories) {
            $category->children_list = $categories->where('parent_id', $category->id)->values();
            return $category;
        });
    }

    /**
     * Get flat list for dropdown
     */
    public static function getDropdownOptions(): array
    {
        return self::active()
            ->ordered()
            ->get()
            ->mapWithKeys(function ($category) {
                $prefix = $category->parent_id ? '— ' : '';
                return [$category->id => $prefix . $category->name];
            })
            ->toArray();
    }
}
