<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentResource;
use App\Models\Category;
use App\Models\CurrentLocation;
use App\Models\Equipment;
use App\Models\SubCategory;
use App\Models\Supplier;
use App\Models\Activity;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Get all equipment with pagination, search, and filter
     */
    public function index(Request $request)
    {
        $query = Equipment::with(['category', 'subCategory', 'supplier', 'currentLocation']);

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by sub_category
        if ($request->has('sub_category_id') && $request->sub_category_id) {
            $query->where('sub_category_id', $request->sub_category_id);
        }

        // Filter by supplier
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by location
        if ($request->has('current_location_id') && $request->current_location_id) {
            $query->where('current_location_id', $request->current_location_id);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('tag_no', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $equipment = $query->paginate($perPage);

        return response()->json([
            'equipment' => EquipmentResource::collection($equipment->items()),
            'total' => $equipment->total(),
            'current_page' => $equipment->currentPage(),
            'per_page' => $equipment->perPage(),
            'last_page' => $equipment->lastPage(),
        ]);
    }

    /**
     * Get form options (categories, suppliers, locations) for select boxes
     */
    public function formOptions()
    {
        return response()->json([
            'categories' => Category::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
            'suppliers' => Supplier::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
            'current_locations' => CurrentLocation::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'building', 'floor', 'room']),
        ]);
    }

    /**
     * Get subcategories by category
     */
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return response()->json([
            'sub_categories' => $subCategories
        ]);
    }

    /**
     * Store a new equipment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag_no' => 'required|string|unique:equipment,tag_no',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'current_location_id' => 'nullable|exists:current_locations,id',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $equipment = Equipment::create($validated);
        $equipment->load(['category', 'subCategory', 'supplier', 'currentLocation']);

        return response()->json([
            'equipment' => new EquipmentResource($equipment)
        ], 201);
    }

    /**
     * Show a single equipment
     */
    public function show(Equipment $equipment)
    {
        $equipment->load(['category', 'subCategory', 'supplier', 'currentLocation']);
        return response()->json([
            'equipment' => new EquipmentResource($equipment)
        ]);
    }

    /**
     * Update an equipment
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'tag_no' => 'sometimes|required|string|unique:equipment,tag_no,' . $equipment->id,
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'current_location_id' => 'nullable|exists:current_locations,id',
            'serial_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $equipment->update($validated);
        $equipment->load(['category', 'subCategory', 'supplier', 'currentLocation']);

        return response()->json([
            'equipment' => new EquipmentResource($equipment)
        ]);
    }

    /**
     * Delete an equipment
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return response()->json([
            'equipment' => ['message' => 'Equipment deleted successfully']
        ], 200);
    }

    /**
     * Get all activities with assignment status for a specific equipment
     * Returns all active activities and indicates which ones are already assigned
     */
    public function getActivities(Equipment $equipment)
    {
        // Get all active activities with their items
        $activities = Activity::where('is_active', true)
            ->with('activityItems')
            ->orderBy('code')
            ->get();

        // Get IDs of activities already assigned to this equipment
        $assignedActivityIds = $equipment->assignedActivities()->pluck('activity_id')->toArray();

        return response()->json([
            'equipment_id' => $equipment->id,
            'equipment_name' => $equipment->name,
            'equipment_tag_no' => $equipment->tag_no,
            'activities' => $activities,
            'assigned_activity_ids' => $assignedActivityIds,
        ]);
    }

    /**
     * Sync activities to equipment - attach new ones and detach unchecked ones
     * Note: Detaching activities does NOT delete existing CheckSheets
     */
    public function attachActivities(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'exists:activities,id',
        ]);

        $newActivityIds = $validated['activity_ids'];
        $currentActivityIds = $equipment->assignedActivities()->pluck('activity_id')->toArray();

        // Find activities to detach (previously selected but now unchecked)
        $toDetach = array_diff($currentActivityIds, $newActivityIds);

        // Find activities to attach (newly selected)
        $toAttach = array_diff($newActivityIds, $currentActivityIds);

        $createdCheckSheets = [];
        $detachedActivities = [];

        // Detach unchecked activities (only remove from pivot table, keep CheckSheets)
        foreach ($toDetach as $activityId) {
            // Only detach from pivot table - DO NOT delete CheckSheets
            $equipment->assignedActivities()->detach($activityId);
            $detachedActivities[] = $activityId;
        }

        // Attach newly selected activities and create CheckSheets
        foreach ($toAttach as $activityId) {
            // Attach activity to equipment
            $equipment->assignedActivities()->attach($activityId);

            // Get activity with its items
            $activity = \App\Models\Activity::with('activityItems')->find($activityId);

            // Create CheckSheet (round 1) with auto-generated sheet_number and null due_date
            $checkSheet = \App\Models\CheckSheet::create([
                'activity_id' => $activityId,
                'equipment_id' => $equipment->id,
                'current_round' => 1,
                'status' => 'Draft', // Default, Complteted, Reviewed, Approved
                'due_date' => null,
                'frequency' => $activity->frequency,
                'activity_code' => $activity->code,
            ]);

            // Create CheckSheetItems from ActivityItems
            foreach ($activity->activityItems as $activityItem) {
                \App\Models\CheckSheetItem::create([
                    'equipment_id' => $equipment->id,
                    'check_sheet_id' => $checkSheet->id,
                    'activity' => $activityItem->activity,
                    'description' => $activityItem->description,
                    'status' => 'Draft', // Default: Rejected
                    'order' => $activityItem->order,
                ]);
            }

            $createdCheckSheets[] = $checkSheet->load('checkSheetItems');
        }

        return response()->json([
            'message' => 'Activities synchronized successfully',
            'attached_count' => count($toAttach),
            'detached_count' => count($toDetach),
            'detached_activity_ids' => $detachedActivities,
            'created_check_sheets' => $createdCheckSheets
        ], 200);
    }
}
