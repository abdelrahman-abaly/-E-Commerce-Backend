<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Repositories\ProductRepository;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(private ProductRepository $productRepository, private MediaService $mediaService) {}

    public function getAll(int $perPage = 15)
    {
        return $this->productRepository->getFiltered($perPage);
    }

    public function findBySlug(string $slug): Product
    {
        $product = $this->productRepository->findBySlug($slug);
        $product->incrementViews();
        return $product;
    }


    public function create(array $data, array $images = []): Product
    {
        $data['slug'] = Str::slug($data['slug'] ?? $data['name']);
        $product = $this->productRepository->create($data);

        if (! empty($images)) {
            $this->mediaService->uploadProductImages($product, $images);
        }
        return $product->load(['category', 'images', 'variants']);
    }

    public function update(Product $product, array $data, array $newImages = []): Product
    {

        if (isset($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }
        $product = $this->productRepository->update($product, $data);

        if (! empty($newImages)) {
            $this->mediaService->uploadProductImages($product, $newImages);
        }
        return $product->load(['category', 'images', 'variants']);
    }

    public function delete(Product $product): bool
    {
        return $this->productRepository->delete($product);
    }

        // ==================== Variants ====================

        public function createVariant(Product $product, array $data): ProductVariant
    {
        // تأكد إن الـ SKU مش موجود
        if (ProductVariant::where('sku', $data['sku'])->exists()) {
            throw new \Exception('هذا الـ SKU مستخدم بالفعل');
        }

        return $product->variants()->create($data);
    }

    public function updateVariant(ProductVariant $variant , array $data):ProductVariant{
        $variant->update($data);
        return $variant->refresh();
    }
  public function deleteVariant(ProductVariant $variant): bool
    {
        return $variant->delete();
    }

    public function setPrimaryImage(Product $product, int $mediaId): void
    {
        $this->mediaService->setPrimary($product, $mediaId);
    }

    public function deleteImage(Product $product, int $mediaId): void
    {
        $this->mediaService->deleteImage($product, $mediaId);
    }

}
