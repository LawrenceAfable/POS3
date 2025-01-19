<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adding Suppliers
        Supplier::create([
            'name' => 'Hugo Lee',
            'email' => 'hugocorp@gmail.com',
            'phone' => '09106401174',
            'address' => '123 Supplier St., City, Country',
        ]);

        Supplier::create([
            'name' => 'Sierra Trading',
            'email' => 'contact@sierra.com',
            'phone' => '09105543321',
            'address' => '456 Trade Blvd., City, Country',
        ]);

        // Adding Categories
        Category::create([
            'name' => 'Merch',
            'description' => 'Goodies from stars around the globe',
        ]);

        Category::create([
            'name' => 'Books',
            'description' => 'A collection of books for every reader.',
        ]);

        // Adding Products
        Product::create([
            'name' => 'Candybong',
            'description' => 'Twice Legacy',
            'price' => 2000,
            'category_id' => 1,  // Assuming Category 'Merch' has ID 1
            'image_url' => '',
            'quantity' => 100,
            'supplier_id' => 1,  // Assuming Supplier 'Hugo Lee' has ID 1
            'sku' => '001',
            'low_stock_threshold' => 10,
        ]);

        Product::create([
            'name' => 'Mystery Novel',
            'description' => 'A gripping mystery novel by a popular author.',
            'price' => 500,
            'category_id' => 2,  // Assuming Category 'Books' has ID 2
            'image_url' => '',
            'quantity' => 200,
            'supplier_id' => 2,  // Assuming Supplier 'Sierra Trading' has ID 2
            'sku' => '002',
            'low_stock_threshold' => 30,
        ]);

        // Adding Customers
        Customer::create([
            'name' => 'John Doe',
            'contact_number' => '1234567890',
        ]);

        Customer::create([
            'name' => 'Jane Smith',
            'contact_number' => '0987654321',
        ]);

        Customer::create([
            'name' => 'Alice Johnson',
            'contact_number' => '1122334455',
        ]);

        Customer::create([
            'name' => 'Bob Brown',
            'contact_number' => '6677889900',
        ]);
    }
}
