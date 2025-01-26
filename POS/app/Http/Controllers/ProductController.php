<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Filter based on search box
        $selectedCategoryId = $request->input('category_id'); // Filter by Category
        $sort = $request->input('sort', 'asc'); // Default to ascending
        $quantityFilter = $request->input('quantity_filter'); // New quantity filter

        // Get all categories and suppliers for the dropdown
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

        // Apply quantity filter (if quantity filter is selected)
        if ($quantityFilter !== null) {
            if ($quantityFilter === '0') {
                $query->where('quantity', 0); // Filter for out-of-stock products
            } elseif ($quantityFilter === '1') {
                $query->where('quantity', '>', 0); // Filter for in-stock products
            }
        }

        // Apply price sorting
        $query->orderBy('price', $sort);

        // Paginate the results (e.g., 10 items per page)
        $products = $query->paginate(10);

        // Return the view with the products and filters
        return view("products.index", [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'search' => $search,
            'selectedCategoryId' => $selectedCategoryId,
            'sort' => $sort,
            'quantityFilter' => $quantityFilter, // Pass the quantity filter to the view
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
            'created_by' => auth()->id(),
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
         //   'quantity' => 'required|integer|min:0',
            'quantity' => 'nullable|integer|min:0',
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
            'updated_by' => auth()->id(),
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
