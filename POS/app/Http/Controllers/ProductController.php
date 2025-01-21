<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index1(Request $request)
    {
        $search = $request->input('search'); // Get the search input

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })->get(); // Fetch filtered products

        $categories = Category::all(); // Fetch all categories
        $suppliers = Supplier::all(); // Fetch all suppliers

        return view("products.index", [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'search' => $search, // Pass the search term back to the view
        ]);
    }

    public function index2(Request $request)
    {
        $search = $request->input('search');
        $selectedCategoryId = $request->input('category_id');

        // Get all categories for the dropdown
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Filter products based on search and selected category
        $products = Product::with(['category', 'supplier'])
            ->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%")
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('supplier', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($selectedCategoryId, function ($query) use ($selectedCategoryId) {
                return $query->where('category_id', $selectedCategoryId);
            })
            ->get();

        return view("products.index", [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'search' => $search, // Pass the search term back to the view
            'selectedCategoryId' => $selectedCategoryId, // Pass the selected category ID to the view
        ]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedCategoryId = $request->input('category_id');

        // Get all categories for the dropdown
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Initialize the query
        $query = Product::with(['category', 'supplier']);

        // Apply the search filter (if search term exists)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply category filter (if category is selected)
        if ($selectedCategoryId) {
            $query->where('category_id', $selectedCategoryId);
        }

        // Get filtered products
        $products = $query->get();

        // Return the view with the products and filters
        return view("products.index", [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'search' => $search,
            'selectedCategoryId' => $selectedCategoryId,
        ]);
    }




    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('products.view', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:100',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,jfif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $imagePath,
            'quantity' => $request->quantity,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function update(Product $product, Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->product_id . ',product_id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->product_id . ',product_id',
            'description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,category_id',
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $product->image_url;
        if ($request->hasFile('image')) {
            if ($imagePath && !filter_var($imagePath, FILTER_VALIDATE_URL)) {
                $filePath = storage_path('app/public/' . $imagePath);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image_url' => $imagePath,
            'quantity' => $request->quantity,
            'supplier_id' => $request->supplier_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image_url && !filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            $imagePath = storage_path('app/public/' . $product->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
