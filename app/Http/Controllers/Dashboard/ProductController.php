<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\ExportService;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ExportService $exportService
    ) {
        $this->middleware('permission:view_products')->only(['index', 'show', 'export']);
        $this->middleware('permission:create_products')->only(['create', 'store']);
        $this->middleware('permission:edit_products')->only(['edit', 'update']);
        $this->middleware('permission:delete_products')->only(['destroy']);
    }

    /**
     * Display a listing of the products
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = $this->productService->searchProducts($search, 10);

        return view('dashboard.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = $this->productService->getActiveCategories();
        return view('dashboard.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(ProductRequest $request)
    {
        try {
            $image = $request->file('product_image');
            
            // Validate image if provided
            if ($image && !$this->productService->validateImage($image)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid image file. Please upload a valid image (jpg, png, gif, webp) under 5MB.');
            }

            $product = $this->productService->createProduct(
                $request->validated(), 
                $image
            );

            return redirect()->route('products.index')
                ->with('success', 'Product "' . $product->name . '" created successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product = $this->productService->getProductWithRelations($product);
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the product
     */
    public function edit(Product $product)
    {
        $categories = $this->productService->getActiveCategories();
        $product = $this->productService->getProductWithRelations($product);
        
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $image = $request->file('product_image');
            
            // Validate image if provided
            if ($image && !$this->productService->validateImage($image)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid image file. Please upload a valid image (jpg, png, gif, webp) under 5MB.');
            }

            $updatedProduct = $this->productService->updateProduct(
                $product, 
                $request->validated(), 
                $image
            );

            return redirect()->route('products.index')
                ->with('success', 'Product "' . $updatedProduct->name . '" updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $result = $this->productService->deleteProduct($product);

        if (request()->ajax()) {
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'product_name' => $result['product_name'] ?? null
            ], $result['success'] ? 200 : 500);
        }

        if ($result['success']) {
            return redirect()->route('products.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('products.index')
            ->with('error', $result['message']);
    }

    /**
     * Export products to Excel
     */
    public function export(): BinaryFileResponse
    {
        return $this->exportService->exportProducts();
    }
}