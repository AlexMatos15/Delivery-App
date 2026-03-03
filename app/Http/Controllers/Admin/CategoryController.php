<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = Category::orderBy('display_order')->orderBy('name')->paginate(15);
        
        return view('admin.categories.index_adminlte', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'display_order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Category created successfully.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'is_active' => ['boolean'],
            'display_order' => ['integer', 'min:0'],
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated successfully.');
    }

    /**
     * Toggle category active status.
     */
    public function toggleActive(Category $category): RedirectResponse
    {
        $category->is_active = !$category->is_active;
        $category->save();

        $status = $category->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.categories.index')->with('status', "Category {$status} successfully.");
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('status', 'Category deleted successfully.');
    }
}
