<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\Order_Detail;
use DB;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view("pos.index", [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers
        ]);
    }

    public function addToCart(Request $request)
    {
        dd($request->all());
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->quantity) {
            return response()->json(['error' => 'Insufficient stock available.'], 400);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $product->price;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'subtotal' => $product->price * $request->quantity,
            ];
        }

        session(['cart' => $cart]);

        return response()->json(['message' => 'Product added to cart.', 'cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|in:cash,card',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        DB::transaction(function () use ($request, $cart) {
            $subtotal = array_sum(array_column($cart, 'subtotal'));
            $discount = $request->discount ?? 0;
            $tax = $subtotal * 0.1;
            $total = $subtotal - $discount + $tax;

            if ($request->amount_paid < $total) {
                throw new \Exception('Insufficient payment.');
            }

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'order_date' => now(),
                'total_amount' => $total,
                'discount' => $discount,
                'tax' => $tax,
                'user_id' => auth()->id(),
            ]);

            foreach ($cart as $productId => $item) {
                Order_Detail::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'discount' => 0,
                    'tax' => $tax,
                ]);

                $product = Product::find($productId);
                $product->decrement('quantity', $item['quantity']);
            }

            Transaction::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount_paid' => $request->amount_paid,
                'change' => $request->amount_paid - $total,
            ]);

            session()->forget('cart');
        });

        return response()->json(['message' => 'Checkout successful.']);
    }


}