<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? url('storage/' . $user->avatar) : null,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'job_start_date' => $user->job_start_date,
                'job_end_date' => $user->job_end_date,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Update the authenticated user's profile information
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'job_start_date' => 'nullable|date',
            'job_end_date' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar ? url('storage/' . $user->avatar) : null,
                'phone' => $user->phone,
                'date_of_birth' => $user->date_of_birth,
                'job_start_date' => $user->job_start_date,
                'job_end_date' => $user->job_end_date,
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ]);
    }

    /**
     * Update the authenticated user's password
     */
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect',
                'errors' => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    /**
     * Upload and update the authenticated user's avatar
     */
    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048', // Max 2MB
        ]);

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update user avatar path
        $user->update([
            'avatar' => $path
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'avatar' => url('storage/' . $path)
        ]);
    }

    /**
     * Delete the authenticated user's avatar
     */
    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        // Delete avatar file if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Clear avatar path from database
        $user->update([
            'avatar' => null
        ]);

        return response()->json([
            'message' => 'Avatar deleted successfully'
        ]);
    }
}
