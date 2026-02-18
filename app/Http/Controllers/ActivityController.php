<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\Discipline;
use App\Models\DisciplineItem;
use App\Models\Equipment;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Get all activities with pagination, search, and filter
     * When equipment_id is provided, returns all active activities with assignment status
     */
    public function index(Request $request)
    {
        // Special case: if equipment_id is provided, return all activities with assignment info
        if ($request->has('equipment_id') && $request->equipment_id) {
            $equipment = Equipment::findOrFail($request->equipment_id);

            // Get all active activities with their items
            $activities = Activity::where('is_active', true)
                ->with('activityItems')
                ->get();

            // Get IDs of activities already assigned to THIS specific equipment
            $assignedActivityIds = $equipment->assignedActivities()
                ->pluck('activity_id')
                ->toArray();

            return response()->json([
                'equipment_id' => $equipment->id,
                'equipment_name' => $equipment->name,
                'equipment_tag_no' => $equipment->tag_no,
                'activities' => $activities,
                'assigned_activity_ids' => $assignedActivityIds,
            ]);
        }

        // Normal pagination flow
        $query = Activity::with(['discipline', 'disciplineItem', 'assignedEquipments', 'activityItems']);

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $activities = $query->paginate($perPage);

        return response()->json([
            'activities' => ActivityResource::collection($activities->items()),
            'total' => $activities->total(),
            'current_page' => $activities->currentPage(),
            'per_page' => $activities->perPage(),
            'last_page' => $activities->lastPage(),
        ]);
    }

    /**
     * Get form options (equipments) for select boxes
     */
    public function formOptions()
    {
        return response()->json([
            'disciplines' => Discipline::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
            'discipline_items' => DisciplineItem::where('is_active', true)
                ->orderBy('discipline_id')
                ->orderBy('code')
                ->get(['id', 'discipline_id', 'code', 'name', 'method']),
            'equipments' => Equipment::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'tag_no']),
        ]);
    }

    /**
     * Store a new activity
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'discipline_item_id' => 'required|exists:discipline_items,id',
            'description' => 'required|string',
            'notes' => 'nullable|string',
            'frequency' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Generate code: {discipline item code}-{frequency padded}
        $disciplineItem = DisciplineItem::findOrFail($validated['discipline_item_id']);
        $frequencyPadded = str_pad($validated['frequency'], 2, '0', STR_PAD_LEFT);
        $validated['code'] = "{$disciplineItem->code}-{$frequencyPadded}";

        $activity = Activity::create($validated);
        $activity->load(['discipline', 'disciplineItem', 'assignedEquipments', 'activityItems']);

        return response()->json([
            'activity' => new ActivityResource($activity)
        ], 201);
    }

    /**
     * Show a single activity
     */
    public function show(Activity $activity)
    {
        $activity->load(['discipline', 'disciplineItem', 'assignedEquipments', 'activityItems']);
        return response()->json([
            'activity' => new ActivityResource($activity)
        ]);
    }

    /**
     * Update an activity
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'discipline_id' => 'sometimes|required|exists:disciplines,id',
            'discipline_item_id' => 'sometimes|required|exists:discipline_items,id',
            'description' => 'sometimes|required|string',
            'notes' => 'nullable|string',
            'frequency' => 'sometimes|required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Regenerate code if discipline_item or frequency changed
        if (
            isset($validated['discipline_item_id']) && $validated['discipline_item_id'] !== $activity->discipline_item_id
            || isset($validated['frequency']) && $validated['frequency'] !== $activity->frequency
        ) {
            $disciplineItemId = $validated['discipline_item_id'] ?? $activity->discipline_item_id;
            $disciplineItem = DisciplineItem::findOrFail($disciplineItemId);
            $frequency = $validated['frequency'] ?? $activity->frequency;
            $frequencyPadded = str_pad($frequency, 2, '0', STR_PAD_LEFT);
            $validated['code'] = "{$disciplineItem->code}-{$frequencyPadded}";
        }

        $activity->update($validated);
        $activity->load(['discipline', 'disciplineItem', 'assignedEquipments', 'activityItems']);

        return response()->json([
            'activity' => new ActivityResource($activity)
        ]);
    }

    /**
     * Delete an activity
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return response()->json([
            'message' => 'Activity deleted successfully'
        ], 200);
    }
}
