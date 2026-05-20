<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProductRepository
{
    public function getFiltered(int $perPage = 15): LengthAwarePaginator
    {
        return QueryBuilder::for(Product::class)
            ->allowedFilters([
                // فلترة بالاسم (search)
                AllowedFilter::partial('name'),

                // فلترة بالـ category
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('category_slug', 'byCategorySlug'),

                // فلترة بالـ price range
                AllowedFilter::scope('min_price', 'minPrice'),
                AllowedFilter::scope('max_price', 'maxPrice'),

                // فلترة بالـ stock
                AllowedFilter::scope('in_stock', 'inStock'),

                // فلترة بالـ featured
                AllowedFilter::exact('is_featured'),
            ])
            ->allowedSorts([
                'price',
                'name',
                AllowedSort::field('newest', 'created_at'),
                AllowedSort::field('popular', 'views_count'),
                AllowedSort::field('rating', 'views_count'), // هنحسبه لاحقاً
            ])
            ->defaultSort('-created_at') // الأحدث أولاً
            ->allowedIncludes(['category', 'images', 'variants'])
            ->where('is_active', true)
            ->with(['primaryImage', 'category'])
            ->withAvg('approvedReviews', 'rating') // متوسط التقييم
            ->withCount('approvedReviews')          // عدد التقييمات
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): Product
    {
        return Product::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'category',
                'images',
                'variants',
                'approvedReviews.user',
            ])
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->firstOrFail();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        return $product->delete(); // Soft Delete
    }
}
