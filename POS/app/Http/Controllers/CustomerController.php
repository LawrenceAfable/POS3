<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index1()
    {
        $customers = Customer::all();
        return view('customers.index', ['customers' => $customers]);
    }

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search input

        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
                // Add more fields if necessary, like filtering by related orders, etc.
            })
            ->paginate(2); // Fetch filtered customers

        return view('customers.index', [
            'customers' => $customers,
            'search' => $search, // Pass the search term back to the view
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:customers,name', // Ensure unique names
            'contact_number' => 'nullable|string|max:50', // Validate contact_number
        ]);

        Customer::create([
            'name' => $request['name'],
            'contact_number' => $request['contact_number'], // Use contact_number
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully!');
    }

    public function update(Customer $customer, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:customers,name,' . $customer->customer_id . ',customer_id', // Ensure unique names
            'contact_number' => 'nullable|string|max:50', // Validate contact_number
        ]);

        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully!');
    }
}
