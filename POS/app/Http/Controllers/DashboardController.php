<?php

namespace App\Http\Controllers;

use App\Models\Order_Detail;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Order;
use DB;
use App\Models\Transaction;

class DashboardController extends Controller
{
    // without graphs
    public function index1()
    {
        // Fetch the necessary data
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalSales = Order_Detail::sum('subtotal');
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'totalCategories' => $totalCategories,
            'totalSuppliers' => $totalSuppliers,
        ]);
    }

    // with graphs
    public function index()
    {
        // Fetch the necessary data
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalSales = Order_Detail::sum('subtotal');
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        // Data for product stock (bar chart)
        $products = Product::select('name', 'quantity')->get();
        $productData = [
            'labels' => $products->pluck('name'),
            'data' => $products->pluck('quantity'),
        ];

        // Data for categories (pie chart)
        $categories = Category::select('name')
            ->withCount('products')
            ->get();
        $categoryData = [
            'labels' => $categories->pluck('name'),
            'data' => $categories->pluck('products_count'),
        ];

        // Sales comparison data (line chart)
        $salesComparison = Order_Detail::selectRaw("CONVERT(VARCHAR, created_at, 23) as date, SUM(subtotal) as total")
            ->groupBy(DB::raw("CONVERT(VARCHAR, created_at, 23)"))
            ->orderBy(DB::raw("CONVERT(VARCHAR, created_at, 23)"), 'asc')
            ->get();

        $salesData = [
            'labels' => $salesComparison->pluck('date'),
            'data' => $salesComparison->pluck('total'),
        ];

        return view('dashboard.index', [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrders' => $totalOrders,
            'totalSales' => $totalSales,
            'totalCategories' => $totalCategories,
            'totalSuppliers' => $totalSuppliers,
            'productData' => $productData,
            'categoryData' => $categoryData,
            'salesData' => $salesData,
        ]);
    }

}
