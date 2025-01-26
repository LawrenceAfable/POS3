<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class POSController extends Controller
{
    // ORIGINAL
    public function index(Request $request)
    {
        // Get the search input
        $search = $request->input('search');

        // Fetch products with or without the search query
        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->where('quantity', '>', 0)  // Only fetch products with quantity greater than 0
            ->paginate(10);  // Paginate with 10 items per page

        // Fetch other necessary data
        $cart = session()->get('cart', []);  // Get cart from session
        $customers = Customer::all();  // Get all customers
        $settings = Setting::first();  // Get the first setting
        $categories = Category::all();  // Get all categories
        $suppliers = Supplier::all();  // Get all suppliers

        // Return the view with all the data
        return view('pos.index', compact('products', 'cart', 'customers', 'settings', 'categories', 'suppliers', 'search'));
    }

    // ORIGINAL, YUNG WALA PA SI TAX AT DISCOUNT SA CART
    public function addToCart(Request $request)
    {
        // Get product ID and quantity from request
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Ensure the quantity is a positive integer and not greater than available stock
        if ($quantity <= 0) {
            return response()->json(['error' => 'Quantity must be greater than zero.'], 400);
        }

        // Find the product from the database
        $product = Product::find($productId);


        // Check if product exists and if there is enough stock
        if (!$product || $product->quantity < $quantity) {
            return response()->json(['error' => 'Insufficient stock for this product.'], 400);
        }

        // Get the current cart from session
        $cart = Session::get('cart', []);

        // If product is already in the cart, update its quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['subtotal'] = $cart[$productId]['quantity'] * $cart[$productId]['price'];
        } else {
            // Otherwise, add a new item to the cart
            $cart[$productId] = [
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $quantity * $product->price,
            ];
        }

        // Save the updated cart to session
        Session::put('cart', $cart);

        return response()->json(['success' => 'Product added to cart.']);
    }

    // ORIGINAL
    public function removeFromCart(Request $request)
    {
        $productId = $request->input('product_id');

        // Remove product from the cart
        $cart = Session::get('cart', []);
        unset($cart[$productId]);

        // Save the updated cart to session
        Session::put('cart', $cart);

        return redirect()->route('pos.index');
    }

    // ORIGINAL
    public function checkout(Request $request)
    {
        $request->validate([
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'charge' => 'required|numeric|min:0',
            'payment_mode' => 'required',
            'customer_id' => 'nullable|exists:customers,customer_id',
        ]);

        // Retrieve the cart from the session
        $cart = Session::get('cart');

        // Check if the cart is empty
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty.']);
        }

        $settings = Setting::first(); // Get the stored tax and discount rates

        // Use the provided tax and discount values, or use the stored ones if not provided
        $tax = $request->tax ?? $settings->tax_rate;
        $discount = $request->discount ?? $settings->discount_rate;


        $paymentMode = $request->payment_mode;
        $customerPayment = $request->charge;
        $customerId = $request->customer_id;

        // Calculate the total price, tax, and discount
        $total = array_sum(array_column($cart, 'subtotal'));
        $taxAmount = $total * ($tax / 100);
        $discountAmount = $total * ($discount / 100);
        $grandTotal = $total + $taxAmount - $discountAmount;

        // Check if the customer payment is sufficient
        if ($customerPayment <= 0 || $customerPayment < $grandTotal) {
            $errorMessage = $customerPayment <= 0
                ? 'Quantity must be greater than zero.'
                : 'Insufficient payment. Please enter an amount equal to or greater than the total.';

            return response()->json(['error' => $errorMessage]);
        }


        // Try to save the order and transaction
        try {

            // Create a new order
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_id' => $customerId, // Save the selected customer ID
                'total_amount' => $grandTotal,
                'payment_mode' => $paymentMode,
                'discount' => $discountAmount,
                'tax' => $taxAmount,
                'order_date' => now(), // Add the current timestamp for order_date
            ]);


            // Save the order details (items in the cart)
            foreach ($cart as $productId => $item) {
                Order_Detail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update the product quantity in the database
                Product::where('product_id', $productId)->decrement('quantity', $item['quantity']);
            }

            // Save the transaction
            Transaction::create([
                'order_id' => $order->order_id,
                'payment_method' => $paymentMode,
                'amount_paid' => $customerPayment,
                'change' => $customerPayment - $grandTotal
            ]);

            // Clear the cart after successful checkout
            Session::forget('cart');

            // Return a success response
            return response()->json(['success' => 'Checkout successful!']);

        } catch (\Exception $e) {
            \Log::error('Checkout failed: ' . $e->getMessage());
            return response()->json(['error' => 'Checkout failed: ' . $e->getMessage()]);
        }
    }




}
