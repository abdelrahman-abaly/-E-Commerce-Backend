<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Product\StoreVariantRequest;
use App\Models\ProductVariant;


class ProductVariantController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index(Product $product): JsonResponse
    {
        return response()->json([
            'data' => ProductVariantResource::collection($product->variants),
        ]);
    }
     public function store(StoreVariantRequest $request, Product $product): JsonResponse
    {
        $variant = $this->service->createVariant($product, $request->validated());

        return response()->json([
            'message' => 'تم إنشاء الـ Variant بنجاح',
            'data'    => new ProductVariantResource($variant),
        ], 201);
    }

    public function update(StoreVariantRequest $request, Product $product, ProductVariant $variant): JsonResponse
    {
        $variant = $this->service->updateVariant($variant, $request->validated());

        return response()->json([
            'message' => 'تم تحديث الـ Variant بنجاح',
            'data'    => new ProductVariantResource($variant),
        ]);
    }

    public function destroy(Product $product, ProductVariant $variant): JsonResponse
    {
        $this->service->deleteVariant($variant);

        return response()->json([
            'message' => 'تم حذف الـ Variant بنجاح',
        ]);
    }
}
