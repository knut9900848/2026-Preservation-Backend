<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleController extends Controller
{
    /**
     * Get user's roles and permissions
     */
    public function getUserRoles(User $user)
    {
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->roles,
            'permissions' => $user->getAllPermissions(),
        ]);
    }

    /**
     * Assign roles to a user
     */
    public function assignRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->syncRoles($roles);

        return response()->json([
            'message' => 'Roles assigned successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->roles,
        ]);
    }

    /**
     * Revoke roles from a user
     */
    public function revokeRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $roles = Role::whereIn('id', $validated['roles'])->get();
        $user->removeRole($roles);

        return response()->json([
            'message' => 'Roles revoked successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'roles' => $user->roles,
        ]);
    }

    /**
     * Assign direct permissions to a user
     */
    public function assignPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'])->get();
        $user->syncPermissions($permissions);

        return response()->json([
            'message' => 'Permissions assigned successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'permissions' => $user->getAllPermissions(),
        ]);
    }

    /**
     * Revoke direct permissions from a user
     */
    public function revokePermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissions = Permission::whereIn('id', $validated['permissions'])->get();
        $user->revokePermissionTo($permissions);

        return response()->json([
            'message' => 'Permissions revoked successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'permissions' => $user->getAllPermissions(),
        ]);
    }

    /**
     * Check if user has a specific permission
     */
    public function checkPermission(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission' => 'required|string',
        ]);

        $hasPermission = $user->hasPermissionTo($validated['permission']);

        return response()->json([
            'has_permission' => $hasPermission,
            'permission' => $validated['permission'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Check if user has a specific role
     */
    public function checkRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string',
        ]);

        $hasRole = $user->hasRole($validated['role']);

        return response()->json([
            'has_role' => $hasRole,
            'role' => $validated['role'],
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
