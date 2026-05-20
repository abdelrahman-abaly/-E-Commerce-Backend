<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'slug'              => $this->slug,
            'description'       => $this->description,
            'short_description' => $this->short_description,
            'price'             => $this->price,
            'compare_price'     => $this->compare_price,
            'discount_percentage' => $this->discount_percentage,
            'cost_price'        => $this->when(
                request()->user()?->isAdmin(),
                $this->cost_price  // السعر الأصلي للأدمن بس
            ),
            'stock'             => $this->stock,
            'sku'               => $this->sku,
            'weight'            => $this->weight,
            'is_active'         => $this->is_active,
            'is_featured'       => $this->is_featured,
            'is_in_stock'       => $this->isInStock(),
            'views_count'       => $this->views_count,

            // التقييم
            'rating'            => round($this->approved_reviews_avg_rating ?? 0, 1),
            'reviews_count'     => $this->approved_reviews_count ?? 0,

            // الـ Category
            'category'          => new CategoryResource($this->whenLoaded('category')),

            // كل الصور
            'images'            => $this->whenLoaded('images', function () {
                return $this->getMedia('images')->map(fn($media) => [
                    'id'         => $media->id,
                    'url'        => $media->getUrl(),
                    'thumb'      => $media->getUrl('thumb'),
                    'medium'     => $media->getUrl('medium'),
                    'is_primary' => $media->getCustomProperty('is_primary', false),
                ]);
            }),

            // الـ Variants
            'variants'          => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),

            // التقييمات
            'reviews'           => ReviewResource::collection(
                $this->whenLoaded('approvedReviews')
            ),

            'created_at'        => $this->created_at->format('Y-m-d'),
            'updated_at'        => $this->updated_at->format('Y-m-d'),
        ];
    }
}
