<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class CategoryService
{
    public function getAllCategoriesWithProductCount(int $perPage = 10): LengthAwarePaginator
    {
        return Category::withCount('products')->paginate($perPage);
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategoriesExcept(int $exceptId)
    {
        return Category::where('id', '!=', $exceptId)->get();
    }

    public function createCategory(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        return Category::create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        $category->update($data);
        return $category->fresh();
    }

    public function getCategoryWithProducts(Category $category): Category
    {
        return $category->loadCount('products')->load('products');
    }

    public function deleteCategory(Category $category): array
    {
        try {
            if ($category->products()->exists()) {
                throw new Exception('Cannot delete category because it has associated products. Please move or delete the products first.');
            }

            if ($category->children()->exists()) {
                throw new Exception('Cannot delete category because it has sub-categories. Please delete the sub-categories first.');
            }

            $categoryName = $category->name;
            $category->delete();

            return [
                'success' => true,
                'message' => 'Category "' . $categoryName . '" deleted successfully.',
                'category_name' => $categoryName
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    public function checkCategoryHasProducts(Category $category): bool
    {
        return $category->products()->exists();
    }

    public function checkCategoryHasChildren(Category $category): bool
    {
        return $category->children()->exists();
    }

    public function generateSlug(string $name): string
    {
        return Str::slug($name);
    }
}
