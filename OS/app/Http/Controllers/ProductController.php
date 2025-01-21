<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display a list of products from fetch API
    public function index()
    {
        try {
            $response = Http::get('http://127.0.0.1:8000/api/products');
            if ($response->successful()) {
                $products = $response->json();
                return view('products.index', compact('products'));
            } else {
                return view('products.index')->withErrors('Failed to fetch products from IMS.');
            }
        } catch (\Exception $e) {
            return view('products.index')->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    // Display a list of products from online store IMS
    public function index1()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        $products = Product::all();

        // Pass the categories and suppliers to the create view
        return view('products.index1', compact('categories', 'suppliers', 'products'));

    }

    // Store a new product in the online database
    public function store(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'sku' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,category_id',
            'quantity' => 'required|integer',
            'supplier_id' => 'nullable|exists:suppliers,supplier_id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload validation
        ]);

        try {
            // Handle the file upload if a file is provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            } else {
                $imagePath = null; // If no image, set as null
            }

            // Create the product entry in the database
            $product = Product::create([
                'sku' => $request->sku,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'supplier_id' => $request->supplier_id,
                'image_url' => $imagePath,  // Store image path
                'low_stock_threshold' => 5, // Optional: Adjust as needed
            ]);

            // Optionally, sync categories or suppliers from in-store
            // If you're syncing data from the in-store API, you can perform that here

            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // Handle any errors that occur during the store process
            return redirect()->route('products.create')->withErrors('Error: ' . $e->getMessage());
        }
    }

    // Show the form to create a new product
    public function create()
    {
        // Fetch all categories and suppliers
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Pass the categories and suppliers to the create view
        return view('products.create', compact('categories', 'suppliers'));
    }
    // Show the form to edit an existing product
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = DB::table('categories')->get();
        $suppliers = DB::table('suppliers')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    // Update an existing product
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sku' => 'required|unique:products,sku,' . $id,
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'image_url' => 'nullable|string',
            'low_stock_threshold' => 'nullable|integer',
        ]);

        try {
            $product = Product::findOrFail($id);
            $product->update($validated);

            $response = Http::put('http://127.0.0.1:8000/api/products/' . $id, $validated);

            if ($response->successful()) {
                return redirect()->route('products.index')->with('success', 'Product successfully updated.');
            } else {
                return back()->withErrors('Failed to update product in the in-store system.');
            }
        } catch (\Exception $e) {
            return back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    // Delete a product
    public function destroy($id)
    {
        try {
            Product::destroy($id);
            $response = Http::delete('http://127.0.0.1:8000/api/products/' . $id);

            if ($response->successful()) {
                return redirect()->route('products.index')->with('success', 'Product successfully deleted.');
            } else {
                return back()->withErrors('Failed to delete product from the in-store system.');
            }
        } catch (\Exception $e) {
            return back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    // Sync products from in-store API to the online store
    public function saveFromApi()
    {
        try {
            $response = Http::get('http://127.0.0.1:8000/api/products');

            if ($response->successful()) {
                $products = $response->json();

                foreach ($products as $product) {
                    // Sync category data
                    if (!empty($product['category'])) {
                        DB::table('categories')->updateOrInsert(
                            ['name' => $product['category']['name']],
                            [
                                'description' => $product['category']['description'] ?? null,
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }

                    // Sync supplier data
                    if (!empty($product['supplier'])) {
                        DB::table('suppliers')->updateOrInsert(
                            ['email' => $product['supplier']['email']],
                            [
                                'name' => $product['supplier']['name'],
                                'phone' => $product['supplier']['phone'] ?? null,
                                'address' => $product['supplier']['address'] ?? null,
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }

                    // Sync product data
                    DB::table('products')->updateOrInsert(
                        ['sku' => $product['sku']],
                        [
                            'name' => $product['name'] ?? 'Unnamed Product',
                            'description' => $product['description'] ?? null,
                            'price' => $product['price'] ?? 0,
                            'quantity' => $product['quantity'] ?? 0,
                            'category_id' => $product['category_id'] ?? null,
                            'supplier_id' => $product['supplier_id'] ?? null,
                            'image_url' => $product['image_url'] ?? null,
                            'low_stock_threshold' => $product['low_stock_threshold'] ?? 5,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    );
                }

                return redirect()->back()->with('success', 'Products successfully synced from the in-store API.');
            } else {
                return redirect()->back()->withErrors('Failed to fetch products from the API.');
            }
        } catch (\Exception $e) {
            \Log::error('Error occurred while saving products: ' . $e->getMessage());
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
