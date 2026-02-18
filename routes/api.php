<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckSheetController;
use App\Http\Controllers\CheckSheetItemController;
use App\Http\Controllers\CurrentLocationController;
use App\Http\Controllers\EquipmentCheckSheetController;
use App\Http\Controllers\EquipmentCheckSheetItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CheckSheetPhotoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckSheetReportController;
use App\Http\Controllers\DisciplineItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    // PDF Test
    Route::get('pdf/test', [PdfController::class, 'testDownload']);

    // CheckSheet Report PDF (Preview)
    Route::get('checksheets/{checkSheet}/report', [CheckSheetReportController::class, 'preview']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    // --------------- Register and Login ----------------//
    Route::post('register', [\App\Http\Controllers\API\AuthenticationController::class, 'register'])->name('register');
    Route::post('login', [\App\Http\Controllers\API\AuthenticationController::class, 'login'])->name('login');

    // ------------------ Get Data ----------------------//
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('get-user', [\App\Http\Controllers\API\AuthenticationController::class, 'userInfo'])->name('get-user');
        Route::post('logout', [\App\Http\Controllers\API\AuthenticationController::class, 'logOut'])->name('logout');
    });
});

// Profile APIs
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar']);

    // Equipment form options and activity management
    Route::get('equipments/form-options', [EquipmentController::class, 'formOptions']);
    Route::get('equipments/categories/{categoryId}/sub-categories', [EquipmentController::class, 'getSubCategories']);
    Route::get('equipments/{equipment}/activities', [EquipmentController::class, 'getActivities']);
    Route::post('equipments/{equipment}/attach-activities', [EquipmentController::class, 'attachActivities']);

    // Equipment CheckSheets - use ?equipment_id=X to get checksheets for specific equipment
    Route::get('equipments/{equipment}/checksheets', [EquipmentCheckSheetController::class, 'getCheckSheets']);

    // Activity form options - use ?equipment_id=X to get activities for specific equipment with assignment status
    Route::get('activities/form-options', [ActivityController::class, 'formOptions']);
    Route::get('activity-items/form-options', [ActivityItemController::class, 'formOptions']);
    Route::get('discipline-items/form-options', [DisciplineItemController::class, 'formOptions']);

    // User form options
    Route::get('users/form-options', [UserController::class, 'formOptions']);

    // Role & Permission Management
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);

    // Assign/Revoke permissions to/from roles
    Route::post('roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions']);
    Route::post('roles/{role}/revoke-permissions', [RoleController::class, 'revokePermissions']);

    // User Role & Permission Management
    Route::get('users/{user}/roles', [UserRoleController::class, 'getUserRoles']);
    Route::post('users/{user}/assign-roles', [UserRoleController::class, 'assignRoles']);
    Route::post('users/{user}/revoke-roles', [UserRoleController::class, 'revokeRoles']);
    Route::post('users/{user}/assign-permissions', [UserRoleController::class, 'assignPermissions']);
    Route::post('users/{user}/revoke-permissions', [UserRoleController::class, 'revokePermissions']);
    Route::post('users/{user}/check-permission', [UserRoleController::class, 'checkPermission']);
    Route::post('users/{user}/check-role', [UserRoleController::class, 'checkRole']);

    // Comments API - Polymorphic comments for any model
    Route::apiResource('comments', CommentController::class);

    // CheckSheets - Global checksheet management (not equipment-scoped)
    Route::get('checksheets/export', [CheckSheetController::class, 'export']);
    Route::get('checksheets', [CheckSheetController::class, 'index']);
    Route::get('checksheets/form-options', [CheckSheetController::class, 'formOptions']);
    Route::get('checksheets/users-by-role', [CheckSheetController::class, 'getUsersByRole']);
    Route::get('checksheets/{checkSheet}', [CheckSheetController::class, 'show']);
    Route::get('checksheets/{checkSheet}/checksheet-items', [CheckSheetController::class, 'getCheckSheetItems']);
    Route::put('checksheets/{checkSheet}/updateDueDate', [CheckSheetController::class, 'updateDueDate']);
    Route::post('checksheets/{checkSheet}/assign-technicians', [CheckSheetController::class, 'assignTechnicians']);
    Route::post('checksheets/{checkSheet}/revoke-technicians', [CheckSheetController::class, 'revokeTechnicians']);
    Route::post('checksheets/{checkSheet}/assign-inspectors', [CheckSheetController::class, 'assignInspectors']);
    Route::post('checksheets/{checkSheet}/revoke-inspectors', [CheckSheetController::class, 'revokeInspectors']);

    // CheckSheet Workflow
    Route::put('checksheets/{checkSheet}/save-draft', [CheckSheetController::class, 'saveDraftCheckSheet']);
    Route::put('checksheets/{checkSheet}/complete', [CheckSheetController::class, 'completeChecksheet']);
    Route::put('checksheets/{checkSheet}/approve', [CheckSheetController::class, 'approveChecksheet']);
    Route::put('checksheets/{checkSheet}/accept', [CheckSheetController::class, 'acceptChecksheet']);
    Route::put('checksheets/{checkSheet}/reject', [CheckSheetController::class, 'rejectChecksheet']);
    Route::get('checksheets/{checkSheet}/history', [CheckSheetController::class, 'getCheckSheetHistory']);
    Route::post('checksheets/{checkSheet}/generate-next-round', [CheckSheetController::class, 'generateNextRoundChecksheet']);

    // CheckSheet Reports
    Route::get('checksheets/{checkSheet}/reports', [CheckSheetReportController::class, 'index']);
    Route::post('checksheets/{checkSheet}/reports', [CheckSheetReportController::class, 'store']);
    Route::get('checksheets/{checkSheet}/reports/{report}/download', [CheckSheetReportController::class, 'download']);

    // CheckSheet Photo Groups
    Route::get('checksheets/{checkSheet}/photo-groups', [CheckSheetPhotoController::class, 'index']);
    Route::post('checksheets/{checkSheet}/photo-groups', [CheckSheetPhotoController::class, 'store']);
    Route::put('checksheets/{checkSheet}/photo-groups/{photoGroup}', [CheckSheetPhotoController::class, 'update']);
    Route::delete('checksheets/{checkSheet}/photo-groups/{photoGroup}', [CheckSheetPhotoController::class, 'destroy']);
    Route::delete('checksheets/{checkSheet}/photo-groups/{photoGroup}/photos/{photo}', [CheckSheetPhotoController::class, 'destroyPhoto']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Settings (singleton - read & update only)
    Route::get('settings', [SettingController::class, 'show']);
    Route::post('settings', [SettingController::class, 'update']);

    // Excel Export
    Route::get('equipments/export', [EquipmentController::class, 'export']);

    // CRUD APIs
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('current-locations', CurrentLocationController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('sub-categories', SubCategoryController::class);
    Route::apiResource('equipments', EquipmentController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('activity-items', ActivityItemController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('disciplines', DisciplineController::class);
    Route::apiResource('discipline-items', DisciplineItemController::class);
});
