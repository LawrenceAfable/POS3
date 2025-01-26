<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search'); // Get the search input
        $categories = Category::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->withCount('products')
            ->paginate(2); // Fetch filtered categories

        return view('categories.index', [
            'categories' => $categories,
            'search' => $search, // Pass the search term back to the view
        ]);
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
