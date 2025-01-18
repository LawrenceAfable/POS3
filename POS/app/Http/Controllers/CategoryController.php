<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name', // Ensure unique category names
            'description' => 'nullable|string|max:500', // Optional description with max length
        ]);

        Category::create([
            'name' => $request['name'],
            'description' => $request['description'],
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function update1(Category $category, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->category_id . ',category_id', // exclude the id
            'description' => 'nullable|string|max:500',

        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function update(Category $category, Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->category_id . ',category_id',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
