<?php

namespace App\Http\Controllers\Admin;

use Inertia\Response as InertiaResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): InertiaResponse
    {
        $categories = Category::all()->toResourceCollection();

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('Admin/Categories/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = new Category();
        $category->name = $validated['name'];
        $category->save();

        return redirect()->route('admin.categories.show', $category->id)
            ->with('success', 'Category created successfully!');
    }

    public function show(string $id): InertiaResponse
    {
        $category = Category::with('posts.user')->findOrFail($id);

        return Inertia::render('Admin/Categories/Show', [
            'category' => $category->toResource(),
        ]);
    }

    public function edit(string $id): InertiaResponse
    {
        $category = Category::with('posts.user')->findOrFail($id);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category->toResource(),
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->name = $validated['name'];
        $category->save();

        return redirect()->route('admin.categories.show', $category->id)
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
