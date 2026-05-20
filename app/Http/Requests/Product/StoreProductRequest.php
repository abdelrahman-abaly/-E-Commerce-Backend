<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        return [
            'category_id'       => ['required', 'exists:categories,id'],
            'name'              => ['required', 'string', 'max:200'],
            'slug'              => ['nullable', 'string', 'unique:products,slug'],
            'description'       => ['nullable', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'price'             => ['required', 'numeric', 'min:0'],
            'compare_price'     => ['nullable', 'numeric', 'gt:price'],
            'cost_price'        => ['nullable', 'numeric', 'min:0'],
            'stock'             => ['required', 'integer', 'min:0'],
            'sku'               => ['nullable', 'string', 'unique:products,sku'],
            'weight'            => ['nullable', 'numeric', 'min:0'],
            'is_active'         => ['boolean'],
            'is_featured'       => ['boolean'],
            'track_quantity'    => ['boolean'],

            // الصور
            'images'            => ['nullable', 'array', 'max:10'],
            'images.*'          => ['image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'compare_price.gt' => 'سعر المقارنة يجب أن يكون أكبر من السعر الحالي',
            'images.max'       => 'لا يمكن رفع أكثر من 10 صور',
            'images.*.max'     => 'حجم الصورة يجب أن لا يتجاوز 2MB',
        ];
    }
}
