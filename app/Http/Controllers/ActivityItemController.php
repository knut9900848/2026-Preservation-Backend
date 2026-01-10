<?php

namespace App\Http\Controllers;

use App\Http\Resources\ActivityItemResource;
use App\Models\Activity;
use App\Models\ActivityItem;
use Illuminate\Http\Request;

class ActivityItemController extends Controller
{
    /**
     * Get all activity items with pagination, search, and filter
     */
    public function index(Request $request)
    {
        $query = ActivityItem::with(['activity']);

        // Filter by activity
        if ($request->has('activity_id') && $request->activity_id) {
            $query->where('activity_id', $request->activity_id);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active !== null) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'order');
        $descending = filter_var($request->get('descending', false), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $activityItems = $query->paginate($perPage);

        return response()->json([
            'activity_items' => ActivityItemResource::collection($activityItems->items()),
            'total' => $activityItems->total(),
            'current_page' => $activityItems->currentPage(),
            'per_page' => $activityItems->perPage(),
            'last_page' => $activityItems->lastPage(),
        ]);
    }

    /**
     * Get form options (activities) for select boxes
     */
    public function formOptions()
    {
        return response()->json([
            'activities' => Activity::where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code', 'description']),
        ]);
    }

    /**
     * Store a new activity item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $activityItem = ActivityItem::create($validated);
        $activityItem->load(['activity']);

        return response()->json([
            'activity_item' => new ActivityItemResource($activityItem)
        ], 201);
    }

    /**
     * Show a single activity item
     */
    public function show(ActivityItem $activityItem)
    {
        $activityItem->load(['activity']);
        return response()->json([
            'activity_item' => new ActivityItemResource($activityItem)
        ]);
    }

    /**
     * Update an activity item
     */
    public function update(Request $request, ActivityItem $activityItem)
    {
        $validated = $request->validate([
            'activity_id' => 'sometimes|required|exists:activities,id',
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'sometimes|required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $activityItem->update($validated);
        $activityItem->load(['activity']);

        return response()->json([
            'activity_item' => new ActivityItemResource($activityItem)
        ]);
    }

    /**
     * Delete an activity item
     */
    public function destroy(ActivityItem $activityItem)
    {
        $activityItem->delete();
        return response()->json([
            'message' => 'Activity item deleted successfully'
        ], 200);
    }
}
