<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = $this->productService->getAll(perPage: 20);

        return response()->json([
            'data' => ProductListResource::collection($products),
            'meta' => [
                'total'        => $products->total(),
                'per_page'     => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create(
            data: $request->except('images'),
            images: $request->file('images', [])
        );

        return response()->json([
            'message' => 'تم إنشاء المنتج بنجاح',
            'data'    => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $$product = $this->productService->update(
            product: $product,
            data: $request->except('images'),
            newImages: $request->file('images', [])
        );
        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح',
            'data'    => new ProductResource($product),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->delete($product);

        return response()->json([
            'message' => 'تم حذف المنتج بنجاح',
        ]);
    }
    // ==================== Images ====================

    public function setPrimaryImage(Request $request, Product $product): JsonResponse
    {
        $request->validate(['media_id' => ['required', 'integer']]);

        $this->productService->setPrimaryImage($product, $request->media_id);

        return response()->json(['message' => 'تم تعيين الصورة الرئيسية']);
    }

    public function deleteImage(Product $product, int $mediaId): JsonResponse
    {
        $this->productService->deleteImage($product, $mediaId);

        return response()->json(['message' => 'تم حذف الصورة بنجاح']);
    }
}
