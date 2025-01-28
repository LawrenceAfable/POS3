<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateReportController extends Controller
{
    // Generating All Products 
    public function productReport($search = null, $category_id = null, $quantity_filter = null, $sort = 'asc')
    {

        // Initialize the query
        $query = Product::with(['category', 'supplier']);

        // Apply the search filter (if search term exists and is not empty)
        if (!empty($search) && $search !== 'null') {
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

        // Apply category filter (if category is selected and is not empty)
        if (!empty($category_id) && $category_id !== 'null') {
            $query->where('category_id', $category_id);
        }

        if (!empty($quantity_filter) && $quantity_filter !== 'null') {
            if ($quantity_filter === '0') {
                $query->where('quantity', '<=', 0); // Filter for zero or below quantity
            } elseif ($quantity_filter === '1') {
                $query->where('quantity', '>', 0); // Filter for in-stock products
            }

            // Debug: Check the quantity filter
            dd([
                'quantity_filter' => $quantity_filter,
                'query' => $query->toSql(),
                'bindings' => $query->getBindings(),
            ]);
        }

        // Apply price sorting
        $query->orderBy('price', $sort);

        // Fetch the filtered products
        $products = $query->get();
        $categories = Category::all();


        // Generate the PDF
        $pdf = PDF::loadView('reports.products.pdf', compact('products', 'categories'));

        // Customize the file name
        $fileName = 'product_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Force download with the custom file name
        return $pdf->download($fileName);
    }

    // Generating Individual Order Report
    public function saleReport($order_id)
    {
        // Fetch the order details using join and leftJoin
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

        // Check if the order exists
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        // Generate the PDF
        $pdf = PDF::loadView('reports.sales.pdf', compact('order'));

        // Customize the file name
        $fileName = 'report_order_' . $order->order_id . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Force download with the custom file name
        return $pdf->download($fileName);
    }

    // Generate Sales Transaction (Daily, Monthly, Yearly)
    public function saleTransactionReport($start_date = null, $end_date = null)
    {
        // Initialize the query for orders
        $query = Order::query();

        // Apply date filter if both start and end dates are provided
        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        // Fetch the filtered orders
        $orders = $query->orderBy('order_date', 'DESC')->get();

        // Generate the PDF
        $pdf = PDF::loadView('reports.saletransacts.pdf', compact('orders'));

        // Customize the file name
        $fileName = 'sale_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        // Force download with the custom file name
        return $pdf->download($fileName);
    }
}
