<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class StockManagementController extends Controller
{
    public function index1()
    {
        return view('stocks.index');
    }

    public function index(Request $request)
    {
        // Get all categories and suppliers
        $categories = Category::all();
        $suppliers = Supplier::all();

        // Raw SQL query to fetch products with user data
        $products = DB::select("
            SELECT 
                products.product_id,
                products.sku,
                products.name AS product_name,
                products.description, -- Include this column
                products.category_id,
                products.supplier_id,
                products.quantity,
                products.price,
                products.low_stock_threshold,
                created_by_user.name AS created_by_name,
                created_by_user.role AS created_by_role,
                updated_by_user.name AS updated_by_name,
                updated_by_user.role AS updated_by_role,
                deleted_by_user.name AS deleted_by_name,
                deleted_by_user.role AS deleted_by_role
            FROM
                products
                LEFT JOIN users AS created_by_user ON created_by_user.id = products.created_by
                LEFT JOIN users AS updated_by_user ON updated_by_user.id = products.updated_by
                LEFT JOIN users AS deleted_by_user ON deleted_by_user.id = products.deleted_by
        ");

        // Return the view with the data
        return view('stocks.index', compact('categories', 'suppliers', 'products'));
    }

    // Restock method
    public function restock1(Request $request)
    {
        $products = $request->input('products');

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            // Update the product quantity in the database
            //      Product::where('product_id', $productId)->increment('quantity', $quantity);
            Product::where('product_id', $productId)->update([
                'quantity' => DB::raw("quantity + $quantity"), // Increment the quantity
                'updated_by' => auth()->id(), // Set the updated_by field to the current user's ID
            ]);
        }

        session()->flash('success', 'Product restock successfully.');
        return redirect()->back()->with('success', 'Products restocked successfully!');
    }

    public function restock(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1', // Ensure products array exists and is not empty
            'products.*.product_id' => 'required|exists:products,product_id', // Ensure product_id is valid
            'products.*.quantity' => 'required|integer|min:0', // Ensure quantity is a non-negative integer
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $products = $request->input('products');
        $noNewStock = true; // Flag to check if any stock was added

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];

            // Skip if quantity is 0
            if ($quantity == 0) {
                continue;
            }

            // Update the product quantity and updated_by field in the database
            Product::where('product_id', $productId)->update([
                'quantity' => DB::raw("quantity + $quantity"), // Increment the quantity
                'updated_by' => auth()->id(), // Set the updated_by field to the current user's ID
                
            ]);

            $noNewStock = false; // Stock was added
        }

        // Check if no new stock was added
        if ($noNewStock) {
            return redirect()->back()->with('info', 'No new stock was added because all quantities were 0.');
        }

        return redirect()->back()->with('success', 'Products restocked successfully!');
    }
}