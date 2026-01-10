<?php

namespace App\Http\Controllers;

use App\Models\CurrentLocation;
use Illuminate\Http\Request;

class CurrentLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = CurrentLocation::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('building', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $locations = $query->paginate($perPage);

        return response()->json([
            'current_locations' => $locations->items(),
            'total' => $locations->total(),
            'current_page' => $locations->currentPage(),
            'per_page' => $locations->perPage(),
            'last_page' => $locations->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:current_locations,code',
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $location = CurrentLocation::create($validated);
        return response()->json([
            'current_location' => $location
        ], 201);
    }

    public function show(CurrentLocation $currentLocation)
    {
        return response()->json([
            'current_location' => $currentLocation
        ]);
    }

    public function update(Request $request, CurrentLocation $currentLocation)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:current_locations,code,' . $currentLocation->id,
            'building' => 'nullable|string|max:255',
            'floor' => 'nullable|string|max:50',
            'room' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $currentLocation->update($validated);
        return response()->json([
            'current_location' => $currentLocation
        ]);
    }

    public function destroy(CurrentLocation $currentLocation)
    {
        $currentLocation->delete();
        return response()->json([
            'current_location' => ['message' => 'Location deleted successfully']
        ], 200);
    }
}
