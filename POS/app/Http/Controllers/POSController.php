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
    // not using
    public function index1()
    {
        $products = Product::where('quantity', '>', 0)->get();
        $cart = session()->get('cart', []);
        $customers = Customer::all();
        $settings = Setting::first(); // Fetch the first (and possibly only) setting row

        return view('pos.index', compact('products', 'cart', 'customers', 'settings'));
    }

    // not using
    public function index2(Request $request)
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

    public function index(Request $request)
    {
        // Get the search input
        $search = $request->input('search');

        // Fetch products with or without the search query
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
            })
            ->where('quantity', '>', 0)  // Only fetch products with quantity greater than 0
            ->get();

        // Fetch other necessary data
        $cart = session()->get('cart', []);  // Get cart from session
        $customers = Customer::all();  // Get all customers
        $settings = Setting::first();  // Get the first setting
        $categories = Category::all();  // Get all categories
        $suppliers = Supplier::all();  // Get all suppliers

        // Return the view with all the data
        return view('pos.index', compact('products', 'cart', 'customers', 'settings', 'categories', 'suppliers', 'search'));
    }

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

    public function generateInvoicePreview(Request $request)
    {
        // Retrieve the cart from the session
        $cart = Session::get('cart');

        // Check if the cart is empty
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty.']);
        }

        // Retrieve settings for tax and discount rates
        $settings = Setting::first();

        // Get the provided tax and discount values, or use default if not provided
        $tax = $request->tax ?? $settings->tax_rate;
        $discount = $request->discount ?? $settings->discount_rate;

        // Calculate the total price, tax, discount, and grand total
        $total = array_sum(array_column($cart, 'subtotal'));
        $taxAmount = $total * ($tax / 100);
        $discountAmount = $total * ($discount / 100);
        $grandTotal = $total + $taxAmount - $discountAmount;

        // Get customer info if selected (or default if not)
        $customer = Customer::find($request->customer_id);

        // Prepare data for the invoice preview
        $invoiceData = [
            'cart' => $cart,
            'tax' => $tax,
            'discount' => $discount,
            'total' => $total,
            'taxAmount' => $taxAmount,
            'discountAmount' => $discountAmount,
            'grandTotal' => $grandTotal,
            'customer' => $customer,
        ];

        // Instead of returning JSON, return the view
        return view('pos.invoice-preview', compact('invoiceData'));
    }



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
        if ($customerPayment < $grandTotal) {
            return response()->json(['error' => 'Insufficient payment. Please enter an amount equal to or greater than the total.']);
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
