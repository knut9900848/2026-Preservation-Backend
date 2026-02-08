<?php

namespace App\Http\Controllers;

use App\Models\CheckSheet;
use App\Models\CheckSheetPhoto;
use App\Models\CheckSheetPhotoGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CheckSheetPhotoController extends Controller
{
    /**
     * Get all photo groups for a checksheet
     * GET /api/checksheets/{checkSheet}/photo-groups
     */
    public function index(CheckSheet $checkSheet)
    {
        $photoGroups = $checkSheet->photoGroups()->with('photos')->get();

        return response()->json([
            'photo_groups' => $photoGroups->map(function ($group) {
                return [
                    'id' => $group->id,
                    'description' => $group->description,
                    'order' => $group->order,
                    'photos' => $group->photos->map(function ($photo) {
                        return [
                            'id' => $photo->id,
                            'filename' => $photo->original_filename,
                            'url' => $photo->url,
                            'size' => $photo->size,
                            'uploaded_at' => $photo->created_at->toISOString(),
                        ];
                    }),
                ];
            }),
        ]);
    }

    /**
     * Create or update a photo group with photos
     * POST /api/checksheets/{checkSheet}/photo-groups
     */
    public function store(Request $request, CheckSheet $checkSheet)
    {
        $validated = $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required|image|max:10240', // 10MB max per image
            'description' => 'nullable|string|max:1000',
            'group_id' => 'nullable|exists:check_sheet_photo_groups,id',
        ]);

        // Get or create photo group
        if ($request->has('group_id') && $request->group_id) {
            $photoGroup = CheckSheetPhotoGroup::where('id', $request->group_id)
                ->where('check_sheet_id', $checkSheet->id)
                ->firstOrFail();

            // Update description if provided
            if ($request->has('description')) {
                $photoGroup->update(['description' => $request->description]);
            }
        } else {
            // Create new group
            $maxOrder = $checkSheet->photoGroups()->max('order') ?? 0;
            $photoGroup = CheckSheetPhotoGroup::create([
                'check_sheet_id' => $checkSheet->id,
                'description' => $request->description ?? '',
                'order' => $maxOrder + 1,
            ]);
        }

        // Upload photos
        $uploadedPhotos = [];
        $maxPhotoOrder = $photoGroup->photos()->max('order') ?? 0;

        foreach ($request->file('photos') as $index => $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs(
                'checksheet-photos/' . $checkSheet->id . '/' . $photoGroup->id,
                $filename,
                'public'
            );

            $photo = CheckSheetPhoto::create([
                'photo_group_id' => $photoGroup->id,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'order' => $maxPhotoOrder + $index + 1,
            ]);

            $uploadedPhotos[] = $photo;
        }

        // Reload photos
        $photoGroup->load('photos');

        return response()->json([
            'message' => 'Photos uploaded successfully',
            'photo_group' => [
                'id' => $photoGroup->id,
                'description' => $photoGroup->description,
                'order' => $photoGroup->order,
                'photos' => $photoGroup->photos->map(function ($photo) {
                    return [
                        'id' => $photo->id,
                        'filename' => $photo->original_filename,
                        'url' => $photo->url,
                        'size' => $photo->size,
                        'uploaded_at' => $photo->created_at->toISOString(),
                    ];
                }),
            ],
        ], 201);
    }

    /**
     * Update photo group description
     * PUT /api/checksheets/{checkSheet}/photo-groups/{photoGroup}
     */
    public function update(Request $request, CheckSheet $checkSheet, CheckSheetPhotoGroup $photoGroup)
    {
        // Verify the photo group belongs to this checksheet
        if ($photoGroup->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Photo group not found for this checksheet'
            ], 404);
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:1000',
        ]);

        $photoGroup->update([
            'description' => $validated['description'] ?? '',
        ]);

        return response()->json([
            'message' => 'Photo group updated successfully',
            'photo_group' => [
                'id' => $photoGroup->id,
                'description' => $photoGroup->description,
            ],
        ]);
    }

    /**
     * Delete a photo group and all its photos
     * DELETE /api/checksheets/{checkSheet}/photo-groups/{photoGroup}
     */
    public function destroy(CheckSheet $checkSheet, CheckSheetPhotoGroup $photoGroup)
    {
        // Verify the photo group belongs to this checksheet
        if ($photoGroup->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Photo group not found for this checksheet'
            ], 404);
        }

        // Delete all photos from storage
        foreach ($photoGroup->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        // Delete the group (photos will be cascade deleted)
        $photoGroup->delete();

        return response()->json([
            'message' => 'Photo group deleted successfully',
        ]);
    }

    /**
     * Delete a single photo from a group
     * DELETE /api/checksheets/{checkSheet}/photo-groups/{photoGroup}/photos/{photo}
     */
    public function destroyPhoto(CheckSheet $checkSheet, CheckSheetPhotoGroup $photoGroup, CheckSheetPhoto $photo)
    {
        // Verify the photo group belongs to this checksheet
        if ($photoGroup->check_sheet_id !== $checkSheet->id) {
            return response()->json([
                'message' => 'Photo group not found for this checksheet'
            ], 404);
        }

        // Verify the photo belongs to this group
        if ($photo->photo_group_id !== $photoGroup->id) {
            return response()->json([
                'message' => 'Photo not found in this group'
            ], 404);
        }

        // Delete from storage
        Storage::disk('public')->delete($photo->path);

        // Delete record
        $photo->delete();

        return response()->json([
            'message' => 'Photo deleted successfully',
        ]);
    }
}
