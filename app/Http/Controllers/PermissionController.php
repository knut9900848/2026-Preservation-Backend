<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Get all permissions
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = $request->get('per_page', 25);
        $permissions = $query->paginate($perPage);

        return response()->json([
            'permissions' => $permissions->items(),
            'total' => $permissions->total(),
            'current_page' => $permissions->currentPage(),
            'per_page' => $permissions->perPage(),
            'last_page' => $permissions->lastPage(),
        ]);
    }

    /**
     * Store a new permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'guard_name' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        return response()->json([
            'permission' => $permission
        ], 201);
    }

    /**
     * Show a single permission
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'permission' => $permission
        ]);
    }

    /**
     * Update a permission
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validated);

        return response()->json([
            'permission' => $permission
        ]);
    }

    /**
     * Delete a permission
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'message' => 'Permission deleted successfully'
        ], 200);
    }
}
