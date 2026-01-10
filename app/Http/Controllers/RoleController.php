<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Get all roles with their permissions
     */
    public function index(Request $request)
    {
        $query = Role::with('permissions');

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
        $roles = $query->paginate($perPage);

        return response()->json([
            'roles' => $roles->items(),
            'total' => $roles->total(),
            'current_page' => $roles->currentPage(),
            'per_page' => $roles->perPage(),
            'last_page' => $roles->lastPage(),
        ]);
    }

    /**
     * Store a new role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => $validated['guard_name'] ?? 'web',
        ]);

        // Sync permissions if provided
        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        $role->load('permissions');

        return response()->json([
            'role' => $role
        ], 201);
    }

    /**
     * Show a single role
     */
    public function show(Role $role)
    {
        $role->load('permissions');

        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * Update a role
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if (isset($validated['name'])) {
            $role->update(['name' => $validated['name']]);
        }

        // Sync permissions if provided
        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        $role->load('permissions');

        return response()->json([
            'role' => $role
        ]);
    }

    /**
     * Delete a role
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ], 200);
    }

    /**
     * Assign permissions to a role
     */
    public function assignPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->syncPermissions($validated['permissions']);
        $role->load('permissions');

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'role' => $role
        ]);
    }

    /**
     * Remove permissions from a role
     */
    public function revokePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'])->get();
        $role->revokePermissionTo($permissions);
        $role->load('permissions');

        return response()->json([
            'message' => 'Permissions revoked successfully',
            'role' => $role
        ]);
    }
}
