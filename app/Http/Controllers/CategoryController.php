<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $categories = $query->paginate($perPage);

        return response()->json([
            'categories' => $categories->items(),
            'total' => $categories->total(),
            'current_page' => $categories->currentPage(),
            'per_page' => $categories->perPage(),
            'last_page' => $categories->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:categories,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category = Category::create($validated);
        return response()->json([
            'category' => $category
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:categories,code,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);
        return response()->json([
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json([
            'category' => ['message' => 'Category deleted successfully']
        ], 200);
    }
}
