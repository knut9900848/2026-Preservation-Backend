<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubCategoryResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SubCategory::with('category');

        // Filter by category_id
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

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
        $subCategories = $query->paginate($perPage);

        return response()->json([
            'sub_categories' => SubCategoryResource::collection($subCategories->items()),
            'total' => $subCategories->total(),
            'current_page' => $subCategories->currentPage(),
            'per_page' => $subCategories->perPage(),
            'last_page' => $subCategories->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sub_categories,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $subCategory = SubCategory::create($validated);
        $subCategory->load('category');

        return response()->json([
            'sub_category' => new SubCategoryResource($subCategory)
        ], 201);
    }

    public function show(SubCategory $subCategory)
    {
        $subCategory->load('category');
        return response()->json([
            'sub_category' => new SubCategoryResource($subCategory)
        ]);
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:sub_categories,code,' . $subCategory->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $subCategory->update($validated);
        $subCategory->load('category');

        return response()->json([
            'sub_category' => new SubCategoryResource($subCategory)
        ]);
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return response()->json([
            'sub_category' => ['message' => 'SubCategory deleted successfully']
        ], 200);
    }
}
