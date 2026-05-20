<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    // قائمة المنتجات مع الـ filters
    public function index(): JsonResponse
    {
        $products = $this->service->getAll(perPage: 15);

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

    // تفاصيل منتج
    public function show(string $slug): JsonResponse
    {
        $product = $this->service->findBySlug($slug);

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    // قائمة الـ categories للـ Customer
    public function categories(): JsonResponse
    {
        $categories = Category::active()
            ->parents()
            ->ordered()
            ->withCount('products')
            ->with('children')
            ->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }

    // منتجات category معينة
    public function byCategory(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $this->service->getAll(perPage: 15);

        return response()->json([
            'category' => new CategoryResource($category),
            'data'     => ProductListResource::collection($products),
        ]);
    }
}
