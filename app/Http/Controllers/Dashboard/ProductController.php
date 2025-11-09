<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the products
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->paginate(10);

        return view('dashboard.products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('dashboard.products.create', compact('categories'));
    }

    /**
     * Store a newly created product
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);
            $product = Product::create($data);

            if ($request->hasFile('product_image')) {
                $imagePath = $product->uploadImage($request->file('product_image'));
                $product->update(['image_path' => $imagePath]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
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
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the product
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view('dashboard.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            $oldSlug = $product->slug;
            $data['slug'] = Str::slug($data['name']);

            $product->update($data);

            if ($oldSlug !== $product->slug && $product->image_path) {
                $product->deleteImageFolder();

                if ($request->hasFile('product_image')) {
                    $imagePath = $product->uploadImage($request->file('product_image'));
                    $product->update(['image_path' => $imagePath]);
                } elseif ($product->image_path && file_exists(public_path($product->image_path))) {
                    $oldImagePath = $product->image_path;
                    $newImagePath = $product->uploadImage(
                        new \Illuminate\Http\UploadedFile(
                            public_path($oldImagePath),
                            basename($oldImagePath)
                        )
                    );
                    $product->update(['image_path' => $newImagePath]);

                    if (file_exists(public_path($oldImagePath))) {
                        unlink(public_path($oldImagePath));
                    }
                }
            }

            if ($request->hasFile('product_image') && $oldSlug === $product->slug) {
                $imagePath = $product->updateImage($request->file('product_image'));
                $product->update(['image_path' => $imagePath]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
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
        try {
            $productName = $product->name;

            $product->deleteImageFolder();
            $product->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product deleted successfully',
                    'product_name' => $productName
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product "' . $productName . '" deleted successfully.');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting product: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('products.index')
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Export products to Excel
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new ProductsExport, 'products_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
