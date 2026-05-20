<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'image'       => $this->image,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
            'parent'      => new CategoryResource($this->whenLoaded('parent')),
            // 'children'    => CategoryResource::collection($this->whenLoaded('children')),
            'children'    => CategoryResource::collection($this->whenLoaded('allChildren')),

            // عدد المنتجات — بيظهر بس لو اتعمله withCount
            'products_count' => $this->whenCounted('products'),

            'created_at'  => $this->created_at->format('Y-m-d'),
        ];
    }
}
