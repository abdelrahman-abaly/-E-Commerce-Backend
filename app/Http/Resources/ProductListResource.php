<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            'short_description' => $this->short_description,
            'price'             => $this->price,
            'compare_price'     => $this->compare_price,
            'discount_percentage' => $this->discount_percentage,
            'stock'             => $this->stock,
            'is_in_stock'       => $this->isInStock(),
            'is_featured'       => $this->is_featured,

            // الصورة الرئيسية بس
            'primary_image'     => $this->whenLoaded('primaryImage', function () {
                return [
                    'url'   => $this->primaryImage?->getUrl(),
                    'thumb' => $this->primaryImage?->getUrl('thumb'),
                ];
            }),

            'category'          => $this->whenLoaded('category', fn() => [
                'id'   => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),

            // بيانات التقييم
            'rating'            => round($this->approved_reviews_avg_rating ?? 0, 1),
            'reviews_count'     => $this->approved_reviews_count ?? 0,
        ];
    }
}
