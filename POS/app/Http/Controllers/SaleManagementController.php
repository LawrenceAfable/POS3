<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use DB;

class SaleManagementController extends Controller
{
    public function index1()
    {
        return view('sales.index');
    }

    public function index()
    {
        // Fetch the sales transactions with necessary details
        $transactions = DB::table('transactions')
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
            ->orderBy('transactions.transaction_id', 'DESC')
            ->get();

        // Pass the transactions data to the view
        return view('sales.index', compact('transactions'));
    }


}
