<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    public function index(Request $request)
    {
        $query = Discipline::query();

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
        $disciplines = $query->paginate($perPage);

        return response()->json([
            'disciplines' => $disciplines->items(),
            'total' => $disciplines->total(),
            'current_page' => $disciplines->currentPage(),
            'per_page' => $disciplines->perPage(),
            'last_page' => $disciplines->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:disciplines,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $discipline = Discipline::create($validated);
        return response()->json([
            'discipline' => $discipline
        ], 201);
    }

    public function show(Discipline $discipline)
    {
        return response()->json([
            'discipline' => $discipline
        ]);
    }

    public function update(Request $request, Discipline $discipline)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|unique:disciplines,code,' . $discipline->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $discipline->update($validated);
        return response()->json([
            'discipline' => $discipline
        ]);
    }

    public function destroy(Discipline $discipline)
    {
        $discipline->delete();
        return response()->json([
            'discipline' => ['message' => 'Discipline deleted successfully']
        ], 200);
    }
}
