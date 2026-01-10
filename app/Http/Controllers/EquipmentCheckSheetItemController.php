<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckSheetItemResource;
use App\Models\CheckSheet;
use App\Models\CheckSheetItem;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentCheckSheetItemController extends Controller
{
    /**
     * Get all check sheet items for a specific check sheet and equipment
     */
    public function index(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $query = CheckSheetItem::with(['equipment', 'checkSheet'])
            ->where('check_sheet_id', $checkSheet->id)
            ->where('equipment_id', $equipment->id);

        // Filter by status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('activity', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'order');
        $descending = filter_var($request->get('descending', false), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $checkSheetItems = $query->paginate($perPage);

        return response()->json([
            'check_sheet_items' => CheckSheetItemResource::collection($checkSheetItems->items()),
            'total' => $checkSheetItems->total(),
            'current_page' => $checkSheetItems->currentPage(),
            'per_page' => $checkSheetItems->perPage(),
            'last_page' => $checkSheetItems->lastPage(),
        ]);
    }

    /**
     * Get form options for select boxes
     */
    public function formOptions(Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        return response()->json([
            'check_sheet' => [
                'id' => $checkSheet->id,
                'sheet_number' => $checkSheet->sheet_number,
                'activity_id' => $checkSheet->activity_id,
            ],
            'equipment' => [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'tag_no' => $equipment->tag_no,
            ],
        ]);
    }

    /**
     * Store a new check sheet item
     */
    public function store(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|integer|min:0|max:3',
            'remarks' => 'nullable|string',
            'order' => 'required|integer|min:1',
        ]);

        // Force equipment_id and check_sheet_id from route parameters
        $validated['equipment_id'] = $equipment->id;
        $validated['check_sheet_id'] = $checkSheet->id;

        $checkSheetItem = CheckSheetItem::create($validated);
        $checkSheetItem->load(['equipment', 'checkSheet']);

        return response()->json([
            'check_sheet_item' => new CheckSheetItemResource($checkSheetItem)
        ], 201);
    }

    /**
     * Show a single check sheet item
     */
    public function show(Equipment $equipment, CheckSheet $checkSheet, CheckSheetItem $checkSheetItem)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Verify the check sheet item belongs to this check sheet
        if ($checkSheetItem->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Check sheet item does not belong to this check sheet'
            ], 404);
        }

        $checkSheetItem->load(['equipment', 'checkSheet']);
        return response()->json([
            'check_sheet_item' => new CheckSheetItemResource($checkSheetItem)
        ]);
    }

    /**
     * Update a check sheet item
     */
    public function update(Request $request, Equipment $equipment, CheckSheet $checkSheet, CheckSheetItem $checkSheetItem)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Verify the check sheet item belongs to this check sheet
        if ($checkSheetItem->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Check sheet item does not belong to this check sheet'
            ], 404);
        }

        $validated = $request->validate([
            'activity' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|integer|min:0|max:3',
            'remarks' => 'nullable|string',
            'order' => 'sometimes|required|integer|min:1',
        ]);

        $checkSheetItem->update($validated);

        // If only status is being updated, return simplified response
        if (count($validated) === 1 && isset($validated['status'])) {
            return response()->json([
                'message' => 'Status updated successfully',
                'check_sheet_item' => [
                    'id' => $checkSheetItem->id,
                    'status' => $checkSheetItem->status,
                ]
            ]);
        }

        // Full update - load relationships and return full resource
        $checkSheetItem->load(['equipment', 'checkSheet']);

        return response()->json([
            'check_sheet_item' => new CheckSheetItemResource($checkSheetItem)
        ]);
    }

    /**
     * Delete a check sheet item
     */
    public function destroy(Equipment $equipment, CheckSheet $checkSheet, CheckSheetItem $checkSheetItem)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Verify the check sheet item belongs to this check sheet
        if ($checkSheetItem->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Check sheet item does not belong to this check sheet'
            ], 404);
        }

        $checkSheetItem->delete();
        return response()->json([
            'message' => 'Check sheet item deleted successfully'
        ], 200);
    }
}
