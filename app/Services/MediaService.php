<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaService
{
    // رفع صور متعددة للمنتج
    public function uploadProductImages(Product $product, array $images): void
    {
        $isFirst = ! $product->getMedia('images')->count();

        foreach ($images as $index => $image) {
            $media = $product
                ->addMedia($image)
                ->usingFileName($this->generateFileName($image))
                ->toMediaCollection('images');

            // أول صورة تبقى الـ primary
            if ($isFirst && $index === 0) {
                $this->setPrimary($product, $media->id);
            }
        }
    }

    // تعيين صورة كـ primary
    public function setPrimary(Product $product, int $mediaId): void
    {
        // شيل الـ primary من الكل
        $product->getMedia('images')->each(function (Media $media) {
            $media->setCustomProperty('is_primary', false)->save();
        });

        // حطها على المطلوب
        $product->getMedia('images')
                ->find($mediaId)
                ?->setCustomProperty('is_primary', true)
                ->save();
    }

    // حذف صورة
    public function deleteImage(Product $product, int $mediaId): void
    {
        $media = $product->getMedia('images')->find($mediaId);

        if (! $media) {
            throw new \Exception('الصورة غير موجودة');
        }

        $wasPrimary = $media->getCustomProperty('is_primary');
        $media->delete();

        // لو الصورة المحذوفة كانت primary، حط أول صورة متبقية كـ primary
        if ($wasPrimary) {
            $remaining = $product->getMedia('images')->first();
            if ($remaining) {
                $remaining->setCustomProperty('is_primary', true)->save();
            }
        }
    }

    private function generateFileName(UploadedFile $file): string
    {
        return uniqid('product_') . '.' . $file->getClientOriginalExtension();
    }
}
