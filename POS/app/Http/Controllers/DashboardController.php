<?php

namespace App\Http\Controllers;

use App\Models\Order_Detail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Order;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch the necessary data
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalSales = Order_Detail::sum('subtotal');
      //  $totalSales = Transaction::sum('amount_paid'); // Assuming you have an amount column in the Transaction model
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
          //  'totalSales' => $totalSales,
            'totalCategories' => $totalCategories,
            'totalSuppliers' => $totalSuppliers,
        ]);
    }
}
