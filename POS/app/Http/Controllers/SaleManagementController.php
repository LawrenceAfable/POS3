<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use DB;

class SaleManagementController extends Controller
{
    public function index1(Request $request)
    {
        // Get the date range from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Initialize the query
        $query = DB::table('transactions')
            ->join('orders', 'transactions.order_id', '=', 'orders.order_id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->select(
                'users.name as user_name',
                'users.role as user_role',
                'products.sku as product_sku',
                'products.name as product_name',
                'transactions.amount_paid',
                'transactions.change',
                'orders.order_date'
            )
            ->orderBy('transactions.transaction_id', 'DESC');

        // Apply date filter if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('orders.order_date', [$startDate, $endDate]);
        }

        // Fetch filtered transactions
        $transactions = $query->paginate(5);

        // Pass the transactions data and date filters to the view
        return view('sales.index', compact('transactions', 'startDate', 'endDate'));
    }

    public function index(Request $request)
    {
        // Get the date range from the request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Initialize the query for orders
        $query = Order::query();

        // Apply date filter if both start and end dates are provided
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }

        // Paginate the results
        $orders = $query->orderBy('order_date', 'DESC')->paginate(10);

        // Pass orders and date filters to the view
        return view('sales.index', compact('orders', 'startDate', 'endDate'));
    }

    public function view($order_id)
    {
        // Fetch the order details using join and leftJoin to match the SQL query
        $order = DB::table('orders')
            ->join('transactions', 'orders.order_id', '=', 'transactions.order_id')
            ->join('order_details', 'orders.order_id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.product_id')
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.customer_id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'orders.order_id',
                'orders.order_date',
                'orders.customer_id',
                DB::raw("COALESCE(customers.name, 'Walk-in') AS customer_name"),
                'orders.total_amount',
                'orders.discount',
                'orders.tax',
                'transactions.amount_paid',
                'transactions.change',
                'transactions.payment_method',
                DB::raw("STRING_AGG(CONCAT(products.name, ' (Qty: ', order_details.quantity, ' x ', order_details.price, ')'), ', ') AS products_brought"),
                'users.name AS username',
                'users.role AS user_role'
            )
            ->where('orders.order_id', $order_id)
            ->groupBy(
                'orders.order_id',
                'orders.order_date',
                'orders.customer_id',
                'customers.name',
                'orders.total_amount',
                'orders.discount',
                'orders.tax',
                'transactions.amount_paid',
                'transactions.change',
                'transactions.payment_method',
                'users.name',
                'users.role'
            )
            ->first();

        // If the order is not found, redirect with an error
        if (!$order) {
            return redirect()->route('sales.index')->with('error', 'Order not found.');
        }

        return view('sales.view', compact('order'));
    }
    
}
