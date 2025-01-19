<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', ['customers' => $customers]);
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
