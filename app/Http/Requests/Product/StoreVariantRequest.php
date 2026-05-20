<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'sku'           => ['required', 'string', 'unique:product_variants,sku'],
            'price'         => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric'],
            'stock'         => ['required', 'integer', 'min:0'],
            'attributes'    => ['required', 'array'],
            'is_active'     => ['boolean'],
        ];
    }
}
