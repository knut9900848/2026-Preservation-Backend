<?php

namespace App\Http\Controllers;

use App\Http\Resources\DisciplineItemResource;
use App\Models\Discipline;
use App\Models\DisciplineItem;
use Illuminate\Http\Request;

class DisciplineItemController extends Controller
{
    public function index(Request $request)
    {
        $query = DisciplineItem::with('discipline');

        // Filter by discipline
        if ($request->has('discipline_id') && $request->discipline_id) {
            $query->where('discipline_id', $request->discipline_id);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('method', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = $request->get('per_page', 100);
        $items = $query->paginate($perPage);

        return response()->json([
            'discipline_items' => DisciplineItemResource::collection($items->items()),
            'total' => $items->total(),
            'current_page' => $items->currentPage(),
            'per_page' => $items->perPage(),
            'last_page' => $items->lastPage(),
        ]);
    }

    public function formOptions()
    {
        return response()->json([
            'disciplines' => Discipline::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'name' => 'required|string|max:255',
            'method' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $discipline = Discipline::findOrFail($validated['discipline_id']);

        // Generate code: {discipline code}-{sequential number 01, 02, ...}
        $nextNumber = $discipline->disciplineItems()->count() + 1;
        $sequentialNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
        $validated['code'] = "{$discipline->code}-{$sequentialNumber}";

        $item = DisciplineItem::create($validated);
        $item->load('discipline');

        return response()->json([
            'discipline_item' => new DisciplineItemResource($item)
        ], 201);
    }

    public function show(DisciplineItem $disciplineItem)
    {
        $disciplineItem->load('discipline');

        return response()->json([
            'discipline_item' => new DisciplineItemResource($disciplineItem)
        ]);
    }

    public function update(Request $request, DisciplineItem $disciplineItem)
    {
        $validated = $request->validate([
            'discipline_id' => 'sometimes|required|exists:disciplines,id',
            'name' => 'sometimes|required|string|max:255',
            'method' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Regenerate code if discipline changed
        if (isset($validated['discipline_id']) && $validated['discipline_id'] !== $disciplineItem->discipline_id) {
            $discipline = Discipline::findOrFail($validated['discipline_id']);
            $nextNumber = $discipline->disciplineItems()->count() + 1;
            $sequentialNumber = str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
            $validated['code'] = "{$discipline->code}-{$sequentialNumber}";
        }

        $disciplineItem->update($validated);
        $disciplineItem->load('discipline');

        return response()->json([
            'discipline_item' => new DisciplineItemResource($disciplineItem)
        ]);
    }

    public function destroy(DisciplineItem $disciplineItem)
    {
        $disciplineItem->delete();

        return response()->json([
            'message' => 'Discipline item deleted successfully'
        ], 200);
    }
}
