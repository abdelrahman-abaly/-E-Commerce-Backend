<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('product')->id;

        return [
            'category_id'       => ['sometimes', 'exists:categories,id'],
            'name'              => ['sometimes', 'string', 'max:200'],
            'slug'              => ['sometimes', 'string', "unique:products,slug,{$id}"],
            'description'       => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'price'             => ['sometimes', 'numeric', 'min:0'],
            'compare_price'     => ['nullable', 'numeric'],
            'cost_price'        => ['nullable', 'numeric', 'min:0'],
            'stock'             => ['sometimes', 'integer', 'min:0'],
            'sku'               => ['nullable', 'string', "unique:products,sku,{$id}"],
            'weight'            => ['nullable', 'numeric'],
            'is_active'         => ['boolean'],
            'is_featured'       => ['boolean'],

            // صور جديدة
            'images'            => ['nullable', 'array'],
            'images.*'          => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
}
