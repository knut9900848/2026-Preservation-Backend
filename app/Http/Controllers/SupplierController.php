<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

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
        // $perPage = $request->get('per_page', 25);
        $perPage = 25;
        $suppliers = $query->paginate($perPage);

        return response()->json([
            'suppliers' => $suppliers->items(),
            'total' => $suppliers->total(),
            'current_page' => $suppliers->currentPage(),
            'per_page' => $suppliers->perPage(),
            'last_page' => $suppliers->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:suppliers,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier = Supplier::create($validated);
        return response()->json([
            'supplier' => $supplier
        ], 201);
    }

    public function show(Supplier $supplier)
    {
        return response()->json([
            'supplier' => $supplier
        ]);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:suppliers,code,' . $supplier->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $supplier->update($validated);
        return response()->json([
            'supplier' => $supplier
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json([
            'supplier' => ['message' => 'Supplier deleted successfully']
        ], 200);
    }
}
