<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', ['suppliers' => $suppliers]);
    }   


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name', // Ensure unique supplier names
            'email' => 'required|string|email|max:255|unique:suppliers,email', // Unique and valid email
            'phone' => 'nullable|string|max:20', // Optional phone number
            'address' => 'nullable|string|max:500', // Optional address with max length
        ]);

        Supplier::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'address' => $request['address'],
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully!');
    }   

    public function update(Supplier $supplier, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->supplier_id . ',supplier_id', // Exclude current ID
            'email' => 'required|string|email|max:255|unique:suppliers,email,' . $supplier->supplier_id . ',supplier_id', // Exclude current email
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully!');
    }

}
