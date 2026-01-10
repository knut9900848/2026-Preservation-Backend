<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckSheetResource;
use App\Http\Resources\CheckSheetItemResource;
use App\Models\Activity;
use App\Models\CheckSheet;
use App\Models\CheckSheetHistory;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;

class EquipmentCheckSheetController extends Controller
{
    /**
     * Get all check sheets for a specific equipment with pagination, search, and filter
     */
    public function getCheckSheets(Request $request, Equipment $equipment)
    {
        $query = CheckSheet::with(['activity', 'equipment', 'reviewer', 'checkSheetItems', 'technicians', 'inspectors'])
            ->where('equipment_id', $equipment->id);

        // Filter by activity
        if ($request->has('activity_id') && $request->activity_id) {
            $query->where('activity_id', $request->activity_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== null) {
            $query->where('status', $request->status);
        }

        // Filter by current_round
        if ($request->has('current_round') && $request->current_round) {
            $query->where('current_round', $request->current_round);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sheet_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $descending = filter_var($request->get('descending', true), FILTER_VALIDATE_BOOLEAN);
        $query->orderBy($sortBy, $descending ? 'desc' : 'asc');

        // Pagination
        $perPage = 25;
        $checkSheets = $query->paginate($perPage);

        return response()->json([
            'check_sheets' => CheckSheetResource::collection($checkSheets->items()),
            'total' => $checkSheets->total(),
            'current_page' => $checkSheets->currentPage(),
            'per_page' => $checkSheets->perPage(),
            'last_page' => $checkSheets->lastPage(),
        ]);
    }

    /**
     * Get form options (activities, users) for select boxes
     */
    public function formOptions(Equipment $equipment)
    {
        return response()->json([
            'activities' => Activity::where('is_active', true)
                ->orderBy('code')
                ->get(['id', 'code', 'description']),
            'users' => User::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    /**
     * Get users filtered by role (technician or inspector)
     */
    public function getUsersByRole(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:technician,inspector',
        ]);

        $users = User::where('is_active', true)
            ->role($validated['role'])
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return response()->json([
            'role' => $validated['role'],
            'users' => $users,
        ]);
    }



    /**
     * Assign technicians to a check sheet
     */
    public function assignTechnicians(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'exists:users,id',
        ]);

        $checkSheet->technicians()->sync($validated['technicians']);
        $checkSheet->load('technicians');

        return response()->json([
            'message' => 'Technicians assigned successfully',
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Revoke technicians from a check sheet
     */
    public function revokeTechnicians(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'technicians' => 'required|array',
            'technicians.*' => 'exists:users,id',
        ]);

        $checkSheet->technicians()->detach($validated['technicians']);
        $checkSheet->load('technicians');

        return response()->json([
            'message' => 'Technicians revoked successfully',
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Assign inspectors to a check sheet
     */
    public function assignInspectors(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:users,id',
        ]);

        $checkSheet->inspectors()->sync($validated['inspectors']);
        $checkSheet->load('inspectors');

        return response()->json([
            'message' => 'Inspectors assigned successfully',
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Revoke inspectors from a check sheet
     */
    public function revokeInspectors(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'inspectors' => 'required|array',
            'inspectors.*' => 'exists:users,id',
        ]);

        $checkSheet->inspectors()->detach($validated['inspectors']);
        $checkSheet->load('inspectors');

        return response()->json([
            'message' => 'Inspectors revoked successfully',
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            })
        ]);
    }

    /**
     * Get check sheet items for a specific check sheet
     * Includes assigned users, due_date, and frequency
     */
    public function getCheckSheetItems(Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors']);

        return response()->json([
            'equipment_id' => $equipment->id,
            'equipment_name' => $equipment->name,
            'equipment_tag_no' => $equipment->tag_no,
            'check_sheet' => new CheckSheetResource($checkSheet),
            'check_sheet_items' => CheckSheetItemResource::collection($checkSheet->checkSheetItems),
            'technicians' => $checkSheet->technicians->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
            'inspectors' => $checkSheet->inspectors->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
            'due_date' => $checkSheet->due_date?->format('Y-m-d'),
            'frequency' => $checkSheet->frequency,
        ]);
    }

    /**
     * Update checksheet due date
     */
    public function updateDueDate(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $validated = $request->validate([
            'due_date' => 'required|date',
        ]);

        $checkSheet->update([
            'due_date' => $validated['due_date'],
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Due date updated successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Complete checksheet - Step 1: Draft -> Completed
     * Updates due_date and all checksheet items status
     */
    public function completeChecksheet(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Validate that checksheet is in draft status
        if ($checkSheet->status !== 'Draft') {
            return response()->json([
                'message' => 'Checksheet must be in draft status to complete'
            ], 400);
        }

        $validated = $request->validate([
            'checksheet_items' => 'required|array',
            'checksheet_items.*.id' => 'required|exists:check_sheet_items,id',
            'checksheet_items.*.status' => 'required|integer|min:0|max:3',
        ]);

        $previousStatus = $checkSheet->status;

        // Update checksheet status to Completed
        $checkSheet->update([
            'status' => 'Completed',
        ]);

        // Update all checksheet items status
        foreach ($validated['checksheet_items'] as $item) {
            \App\Models\CheckSheetItem::where('id', $item['id'])
                ->where('check_sheet_id', $checkSheet->id)
                ->where('equipment_id', $equipment->id)
                ->update(['status' => $item['status']]);
        }

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'completed',
            'from_status' => $previousStatus,
            'to_status' => 'Completed',
            'metadata' => [
                'items_updated' => count($validated['checksheet_items']),
            ],
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Checksheet completed successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Review checksheet - Step 2: Completed -> Reviewed
     */
    public function reviewChecksheet(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Validate that checksheet is in completed status
        if ($checkSheet->status !== 'Completed') {
            return response()->json([
                'message' => 'Checksheet must be in completed status to review'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        // Update checksheet to reviewed status
        $checkSheet->update([
            'status' => 'Reviewed',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'reviewed',
            'from_status' => $previousStatus,
            'to_status' => 'Reviewed',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Checksheet reviewed successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Approve checksheet - Step 3: Reviewed -> Approved
     */
    public function approveChecksheet(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Validate that checksheet is in reviewed status
        if ($checkSheet->status !== 'Reviewed') {
            return response()->json([
                'message' => 'Checksheet must be in reviewed status to approve'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        // Update checksheet to approved status
        $checkSheet->update([
            'status' => 'Approved',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'approved',
            'from_status' => $previousStatus,
            'to_status' => 'Approved',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment', 'reviewer']);

        return response()->json([
            'message' => 'Checksheet approved successfully',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Reject checksheet - Return to Draft status
     */
    public function rejectChecksheet(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Validate that checksheet is not already in draft or approved status
        if ($checkSheet->status === 'Draft') {
            return response()->json([
                'message' => 'Checksheet is already in draft status'
            ], 400);
        }

        if ($checkSheet->status === 'Approved') {
            return response()->json([
                'message' => 'Cannot reject an approved checksheet'
            ], 400);
        }

        $previousStatus = $checkSheet->status;

        // Update checksheet back to draft status
        $checkSheet->update([
            'status' => 'Draft',
        ]);

        // Record history
        CheckSheetHistory::create([
            'check_sheet_id' => $checkSheet->id,
            'user_id' => $request->user()->id,
            'action' => 'rejected',
            'from_status' => $previousStatus,
            'to_status' => 'Draft',
        ]);

        $checkSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Checksheet rejected and returned to draft',
            'check_sheet' => new CheckSheetResource($checkSheet),
        ]);
    }

    /**
     * Get checksheet history - all status changes and who performed them
     */
    public function getCheckSheetHistory(Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        $histories = CheckSheetHistory::where('check_sheet_id', $checkSheet->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'histories' => $histories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'action' => $history->action,
                    'from_status' => $history->from_status,
                    'to_status' => $history->to_status,
                    'user' => [
                        'id' => $history->user->id,
                        'name' => $history->user->name,
                        'email' => $history->user->email,
                    ],
                    'metadata' => $history->metadata,
                    'created_at' => $history->created_at,
                ];
            }),
        ]);
    }

    /**
     * Generate next round checksheet
     * Copies activity items and settings from current round and increments round number
     */
    public function generateNextRoundChecksheet(Request $request, Equipment $equipment, CheckSheet $checkSheet)
    {
        // Verify the check sheet belongs to this equipment
        if ($checkSheet->equipment_id !== $equipment->id) {
            return response()->json([
                'message' => 'Check sheet does not belong to this equipment'
            ], 404);
        }

        // Validate that current checksheet status is Approved
        if ($checkSheet->status !== 'Approved') {
            return response()->json([
                'message' => 'Checksheet must be approved before generating next round'
            ], 400);
        }

        // Verify that this is the latest round for this equipment and activity
        $latestRound = CheckSheet::where('equipment_id', $equipment->id)
            ->where('activity_id', $checkSheet->activity_id)
            ->max('current_round');

        if ($checkSheet->current_round !== $latestRound) {
            return response()->json([
                'message' => 'Only the latest round checksheet can generate next round',
                'current_round' => $checkSheet->current_round,
                'latest_round' => $latestRound,
            ], 400);
        }

        // Verify that the activity is still assigned to this equipment
        $activityExists = $equipment->assignedActivities()
            ->where('activity_id', $checkSheet->activity_id)
            ->exists();

        if (!$activityExists) {
            return response()->json([
                'message' => 'Activity is no longer assigned to this equipment'
            ], 400);
        }

        // Load relationships needed for copying
        $checkSheet->load(['activity', 'technicians', 'inspectors', 'checkSheetItems']);

        // Calculate next round number
        $nextRound = $checkSheet->current_round + 1;

        // Calculate next due date by adding frequency (in days) to current due_date
        $nextDueDate = null;
        if ($checkSheet->due_date && $checkSheet->frequency) {
            $nextDueDate = \Carbon\Carbon::parse($checkSheet->due_date)
                ->addDays($checkSheet->frequency)
                ->format('Y-m-d');
        }

        // Create new CheckSheet for next round
        $newCheckSheet = CheckSheet::create([
            'activity_id' => $checkSheet->activity_id,
            'equipment_id' => $checkSheet->equipment_id,
            'current_round' => $nextRound,
            'status' => 'Draft',
            'due_date' => $nextDueDate,
            'frequency' => $checkSheet->frequency,
            'activity_code' => $checkSheet->activity_code,
        ]);

        // Copy CheckSheetItems from previous round
        foreach ($checkSheet->checkSheetItems as $item) {
            \App\Models\CheckSheetItem::create([
                'equipment_id' => $equipment->id,
                'check_sheet_id' => $newCheckSheet->id,
                'activity' => $item->activity,
                'description' => $item->description,
                'status' => 'Draft',
                'order' => $item->order,
            ]);
        }

        // Copy assigned technicians from previous round
        if ($checkSheet->technicians->isNotEmpty()) {
            $technicianIds = $checkSheet->technicians->pluck('id')->toArray();
            $newCheckSheet->technicians()->sync($technicianIds);
        }

        // Copy assigned inspectors from previous round
        if ($checkSheet->inspectors->isNotEmpty()) {
            $inspectorIds = $checkSheet->inspectors->pluck('id')->toArray();
            $newCheckSheet->inspectors()->sync($inspectorIds);
        }

        // Load relationships for response
        $newCheckSheet->load(['checkSheetItems', 'technicians', 'inspectors', 'activity', 'equipment']);

        return response()->json([
            'message' => 'Next round checksheet generated successfully',
            'check_sheet' => new CheckSheetResource($newCheckSheet),
            'previous_round' => $checkSheet->current_round,
            'new_round' => $nextRound,
        ]);
    }
}
