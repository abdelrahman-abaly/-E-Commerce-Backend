<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;


class Product extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost_price',
        'stock',
        'sku',
        'weight',
        'is_active',
        'is_featured',
        'track_quantity',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'compare_price'  => 'decimal:2',
            'cost_price'     => 'decimal:2',
            'is_active'      => 'boolean',
            'is_featured'    => 'boolean',
            'track_quantity' => 'boolean',
        ];
    }

    // ==================== Relationships ====================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function activeVariants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ==================== Scopes ====================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // public function scopeInStock($query)
    // {
    //     return $query->where('stock', '>', 0);
    // }

    // public function scopePriceBetween($query, $min, $max)
    // {
    //     return $query->whereBetween('price', [$min, $max]);
    // }

    public function scopeByCategorySlug($query, string $slug)
    {
        return $query->whereHas('category', fn($q) => $q->where('slug', $slug));
    }

    public function scopeMinPrice($query, float $price)
    {
        return $query->where('price', '>=', $price);
    }

    public function scopeMaxPrice($query, float $price)
    {
        return $query->where('price', '<=', $price);
    }

    public function scopeInStock($query, bool $value = true)
    {
        return $value ? $query->where('stock', '>', 0) : $query->where('stock', '<', 0);
    }






    // ==================== Helper Methods ====================

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    // public function hasDiscount(): bool
    // {
    //     return ! is_null($this->compare_price) && $this->compare_price > $this->price;
    // }

    // public function getDiscountPercentageAttribute(): ?int
    // {
    //     if (! $this->hasDiscount()) return null;

    //     return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    // }

    // public function getAverageRatingAttribute(): float
    // {
    //     return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    // }

    public function incrementViews(): void
    {
        $this->increment('views_count');
    }



    // =========================== Media Library ======================


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(800)
            ->height(800)
            ->nonQueued();
    }

    // ========================================================================


    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}
