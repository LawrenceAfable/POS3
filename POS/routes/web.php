<?php


use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\StockManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SaleManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Auth;

// Default route
Route::get('/', function () {
    return view('auth.login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/login-user', [AuthController::class, 'loginUser'])->name('loginUser');

Route::get('/registration', [AuthController::class, 'registration'])->name('auth.registration');
Route::post('/register-user', [AuthController::class, 'registerUser'])->name('registerUser');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('auth.login');
})->name('logout');

// Routes accessible only to authenticated users
Route::middleware(['auth'])->group(function () { // change the auth to ['auth', 'role:admin'] if only the admin can access dashboard

    // // Common Dashboard
    // Route::get('/dashboard', function () {
    //     return view('dashboard.index');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile
    Route::get('/user-profile', [AuthController::class, 'profile'])->name('users.profile');
    Route::put('/user-profile/{id}/update', [AuthController::class, 'update'])->name('users1.update');

    // POS (Accessible to Admin and Cashier)
    Route::middleware(['auth', 'role:admin,cashier'])->group(function () {
        // POS Routes
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::post('/pos/add-to-cart', [POSController::class, 'addToCart'])->name('pos.addToCart');
        Route::post('/pos/remove-from-cart', [POSController::class, 'removeFromCart'])->name('pos.removeFromCart');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    });

    // Admin-Only Routes
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Categories
        Route::get('/category', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/category', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/category/{category}/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/category/{category}/destroy', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Suppliers
        Route::get('/supplier', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('/supplier/{supplier}/update', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/supplier/{supplier}/destroy', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        // Products
        Route::get('products/{product_id}/view', [ProductController::class, 'show'])->name('products.view');
        Route::get('/product', [ProductController::class, 'index'])->name('products.index');
        Route::post('/product', [ProductController::class, 'store'])->name('products.store');
        Route::put('/product/{product}/update', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/product/{product}/destroy', [ProductController::class, 'destroy'])->name('products.destroy');

        // Customers
        Route::get('/customer', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customer', [CustomerController::class, 'store'])->name('customers.store');
        Route::put('/customer/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customer/{customer}/destroy', [CustomerController::class, 'destroy'])->name('customers.destroy');

        // User Management
        Route::get('/user-create', [UserManagementController::class, 'add'])->name('admin.userrole.modals.add');
        Route::get('/user-edit/{id}', [UserManagementController::class, 'edit'])->name('admin.userrole.modals.edit'); // Add {id} parameter
        Route::get('/user-edit', [UserManagementController::class, 'edit'])->name('admins.userrole.modals.edit');

        // ORIGINAL
        Route::get('/user-dashboard', [UserManagementController::class, 'index'])->name('admin.userrole.index');
        Route::post('/user-add', [UserManagementController::class, 'store'])->name('users.store');
        Route::put('/user/{id}/update', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/user/{id}/destroy', [UserManagementController::class, 'destroy'])->name('users.destroy');

        // Sales Transaction
        Route::get('/sales-transaction', [SaleManagementController::class, 'index'])->name('sales.index');
        Route::get('sales/{order_id}/view', [SaleManagementController::class, 'view'])->name('sales.view');

        // Setting
        Route::get('/setting', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Invoice
        Route::post('/pos/preview', [POSController::class, 'showPreview'])->name('showPreview');

        // Generate Report
        Route::get('/product-report/{search?}/{category_id?}/{quantity_filter?}/{sort?}', [GenerateReportController::class, 'productReport'])
            ->name('productReport');
        Route::get('/sale-transaction-report/{start_date?}/{end_date?}', [GenerateReportController::class, 'saleTransactionReport'])
            ->name('saleTransactionReport');
        Route::get('/sale-report/{order_id}', [GenerateReportController::class, 'saleReport'])
            ->name('saleReport');
        // Stocks Management
        Route::get('/stock', [StockManagementController::class, 'index'])->name('stocks.index');
        Route::post('/stock/restock', [StockManagementController::class, 'restock'])->name('stocks.restock');

    });
});
