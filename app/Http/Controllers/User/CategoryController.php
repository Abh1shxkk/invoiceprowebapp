<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::orderBy('type')->orderBy('name')->paginate(20);

        return view('user.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('user.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'type' => ['required', 'in:expense,income'],
        ]);

        Category::create($validated);

        return redirect()->route('user.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('user.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'type' => ['required', 'in:expense,income'],
        ]);

        $category->update($validated);

        return redirect()->route('user.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has expenses
        if ($category->expenses()->count() > 0) {
            return redirect()->route('user.categories.index')
                ->with('error', 'Cannot delete category with existing expenses.');
        }

        $category->delete();

        return redirect()->route('user.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
