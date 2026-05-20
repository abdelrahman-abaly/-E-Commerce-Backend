<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function __construct(private CategoryService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = $this->service->getTree();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->service->create($request->validated());
        return response()->json([
            'message' => 'تم إنشاء القسم بنجاح',
            'data' => new CategoryResource($category),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category):JsonResponse
    {
        // $category->load(['parent','children','products']);
        $category->load(['parent','allChildren'])->loadCount(['products']);
        return response()->json([
            'data'=> new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category = $this->service->update($category, $request->validated());

        return response()->json([
            'message' => 'تم تحديث القسم بنجاح',
            'data'    => new CategoryResource($category),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Category $category):JsonResponse
    {
        $this->service->delete($category);
        return response()->json([
            'message' => 'تم حذف القسم بنجاح',
        ]);
    }
}
