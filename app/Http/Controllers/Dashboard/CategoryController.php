<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function __construct(private CategoryService $categoryService)
    {
        $this->middleware('permission:view_categories')->only(['index', 'show']);
        $this->middleware('permission:create_categories')->only(['create', 'store']);
        $this->middleware('permission:edit_categories')->only(['edit', 'update']);
        $this->middleware('permission:delete_categories')->only(['destroy']);
    }

    /**
     * Display a listing of the categories
     */
    public function index()
    {
        $categories = $this->categoryService->getAllCategoriesWithProductCount(10);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        return view('dashboard.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category
     */
    public function store(CategoryRequest $request)
    {
        $category = $this->categoryService->createCategory($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category "' . $category->name . '" created successfully.');
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category = $this->categoryService->getCategoryWithProducts($category);
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the category
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryService->getCategoriesExcept($category->id);
        return view('dashboard.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified category
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $updatedCategory = $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category "' . $updatedCategory->name . '" updated successfully.');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        $result = $this->categoryService->deleteCategory($category);

        if ($result['success']) {
            return redirect()->route('categories.index')
                ->with('success', $result['message']);
        }

        return redirect()->back()
            ->with('error', $result['message']);
    }
}
