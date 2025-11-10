<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductService
{
    public function searchProducts(?string $search = null, int $perPage = 10): LengthAwarePaginator
    {
        return Product::with('category')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->paginate($perPage);
    }

    public function getActiveCategories()
    {
        return Category::active()->get();
    }

    public function createProduct(array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($data, $image) {
            $data['slug'] = Str::slug($data['name']);
            
            $product = Product::create($data);

            if ($image) {
                $imagePath = $product->uploadImage($image);
                $product->update(['image_path' => $imagePath]);
            }

            return $product->fresh();
        });
    }

    public function updateProduct(Product $product, array $data, ?UploadedFile $image = null): Product
    {
        return DB::transaction(function () use ($product, $data, $image) {
            $oldSlug = $product->slug;
            $data['slug'] = Str::slug($data['name']);

            $product->update($data);

            // Handle image operations based on slug change
            $this->handleImageOperations($product, $oldSlug, $image);

            return $product->fresh();
        });
    }

    public function deleteProduct(Product $product): array
    {
        try {
            $productName = $product->name;

            // Delete associated images
            $product->deleteImageFolder();
            
            $product->delete();

            return [
                'success' => true,
                'message' => 'Product "' . $productName . '" deleted successfully.',
                'product_name' => $productName
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting product: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    public function getProductWithRelations(Product $product, array $relations = ['category']): Product
    {
        return $product->load($relations);
    }

    private function handleImageOperations(Product $product, string $oldSlug, ?UploadedFile $image = null): void
    {
        // If slug changed and product has an image
        if ($oldSlug !== $product->slug && $product->image_path) {
            $this->handleSlugChangeImageUpdate($product, $image);
        } 
        // If new image uploaded and slug didn't change
        elseif ($image && $oldSlug === $product->slug) {
            $this->handleImageUpdate($product, $image);
        }
    }

    private function handleSlugChangeImageUpdate(Product $product, ?UploadedFile $image = null): void
    {
        // Delete old image folder
        $product->deleteImageFolder();

        if ($image) {
            // Upload new image
            $imagePath = $product->uploadImage($image);
            $product->update(['image_path' => $imagePath]);
        } elseif ($product->image_path && file_exists(public_path($product->image_path))) {
            // Re-upload existing image with new path
            $oldImagePath = $product->image_path;
            $newImagePath = $product->uploadImage(
                new UploadedFile(
                    public_path($oldImagePath),
                    basename($oldImagePath)
                )
            );
            $product->update(['image_path' => $newImagePath]);

            // Delete old image file
            if (file_exists(public_path($oldImagePath))) {
                unlink(public_path($oldImagePath));
            }
        }
    }

    private function handleImageUpdate(Product $product, UploadedFile $image): void
    {
        $imagePath = $product->updateImage($image);
        $product->update(['image_path' => $imagePath]);
    }

    public function validateImage(?UploadedFile $image = null): bool
    {
        if (!$image) {
            return true;
        }

        $allowedMimes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024; // 5MB in KB

        return in_array($image->getClientOriginalExtension(), $allowedMimes) && 
               $image->getSize() <= $maxSize;
    }

    public function generateSlug(string $name): string
    {
        return Str::slug($name);
    }
}