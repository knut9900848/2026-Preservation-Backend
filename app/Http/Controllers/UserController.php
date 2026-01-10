<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get all users with pagination, search, and filter
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $users = $query->paginate($perPage);

        return response()->json([
            'users' => UserResource::collection($users->items()),
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'last_page' => $users->lastPage(),
        ]);
    }

    /**
     * Get form options for select boxes
     */
    public function formOptions()
    {
        return response()->json([
            // Add any form options here if needed
        ]);
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'job_start_date' => 'nullable|date',
            'job_end_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        // Hash password
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * Show a single user
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Update a user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|string|min:8',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'job_start_date' => 'nullable|date',
            'job_end_date' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        // Delete avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
