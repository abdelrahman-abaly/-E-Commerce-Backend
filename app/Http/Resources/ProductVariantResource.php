<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'sku'             => $this->sku,
            'price'           => $this->price,
            'compare_price'   => $this->compare_price,
            'stock'           => $this->stock,
            'attributes'      => $this->attributes,
            'attributes_label'=> $this->attributes_label,
            'image'           => $this->image,
            'is_active'       => $this->is_active,
            'is_in_stock'     => $this->isInStock(),
        ];
    }
}
