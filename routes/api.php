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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['namespace' => 'App\Http\Controllers\API'], function () {
    // --------------- Register and Login ----------------//
    Route::post('register', 'AuthenticationController@register')->name('register');
    Route::post('login', 'AuthenticationController@login')->name('login');

    // ------------------ Get Data ----------------------//
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('get-user', 'AuthenticationController@userInfo')->name('get-user');
        Route::post('logout', 'AuthenticationController@logOut')->name('logout');
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
    Route::get('equipments/{equipment}/checksheets/form-options', [EquipmentCheckSheetController::class, 'formOptions']);
    Route::get('equipments/{equipment}/checksheets/users-by-role', [EquipmentCheckSheetController::class, 'getUsersByRole']);
    Route::get('equipments/{equipment}/checksheets/{checkSheet}/checksheet-items', [EquipmentCheckSheetController::class, 'getCheckSheetItems']);
    Route::put('equipments/{equipment}/checksheets/{checkSheet}/updateDueDate', [EquipmentCheckSheetController::class, 'updateDueDate']);
    Route::post('equipments/{equipment}/checksheets/{checkSheet}/assign-technicians', [EquipmentCheckSheetController::class, 'assignTechnicians']);
    Route::post('equipments/{equipment}/checksheets/{checkSheet}/revoke-technicians', [EquipmentCheckSheetController::class, 'revokeTechnicians']);
    Route::post('equipments/{equipment}/checksheets/{checkSheet}/assign-inspectors', [EquipmentCheckSheetController::class, 'assignInspectors']);
    Route::post('equipments/{equipment}/checksheets/{checkSheet}/revoke-inspectors', [EquipmentCheckSheetController::class, 'revokeInspectors']);

    // Equipment CheckSheet Workflow
    Route::put('equipments/{equipment}/checksheets/{checkSheet}/complete', [EquipmentCheckSheetController::class, 'completeChecksheet']);
    Route::put('equipments/{equipment}/checksheets/{checkSheet}/review', [EquipmentCheckSheetController::class, 'reviewChecksheet']);
    Route::put('equipments/{equipment}/checksheets/{checkSheet}/approve', [EquipmentCheckSheetController::class, 'approveChecksheet']);
    Route::put('equipments/{equipment}/checksheets/{checkSheet}/reject', [EquipmentCheckSheetController::class, 'rejectChecksheet']);
    Route::get('equipments/{equipment}/checksheets/{checkSheet}/history', [EquipmentCheckSheetController::class, 'getCheckSheetHistory']);
    Route::post('equipments/{equipment}/checksheets/{checkSheet}/generate-next-round', [EquipmentCheckSheetController::class, 'generateNextRoundChecksheet']);


    // Equipment CheckSheet Items
    Route::apiResource('equipments/{equipment}/checksheets/{checkSheet}/checksheet-items', EquipmentCheckSheetItemController::class);

    // Activity form options - use ?equipment_id=X to get activities for specific equipment with assignment status
    Route::get('activities/form-options', [ActivityController::class, 'formOptions']);
    Route::get('activity-items/form-options', [ActivityItemController::class, 'formOptions']);

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
    Route::get('checksheets', [CheckSheetController::class, 'index']);
    Route::get('checksheets/form-options', [CheckSheetController::class, 'formOptions']);
    Route::get('checksheets/users-by-role', [CheckSheetController::class, 'getUsersByRole']);
    Route::get('checksheets/{checkSheet}/checksheet-items', [CheckSheetController::class, 'getCheckSheetItems']);
    Route::put('checksheets/{checkSheet}/updateDueDate', [CheckSheetController::class, 'updateDueDate']);
    Route::post('checksheets/{checkSheet}/assign-technicians', [CheckSheetController::class, 'assignTechnicians']);
    Route::post('checksheets/{checkSheet}/revoke-technicians', [CheckSheetController::class, 'revokeTechnicians']);
    Route::post('checksheets/{checkSheet}/assign-inspectors', [CheckSheetController::class, 'assignInspectors']);
    Route::post('checksheets/{checkSheet}/revoke-inspectors', [CheckSheetController::class, 'revokeInspectors']);

    // CheckSheet Workflow
    Route::put('checksheets/{checkSheet}/complete', [CheckSheetController::class, 'completeChecksheet']);
    Route::put('checksheets/{checkSheet}/review', [CheckSheetController::class, 'reviewChecksheet']);
    Route::put('checksheets/{checkSheet}/approve', [CheckSheetController::class, 'approveChecksheet']);
    Route::put('checksheets/{checkSheet}/reject', [CheckSheetController::class, 'rejectChecksheet']);
    Route::get('checksheets/{checkSheet}/history', [CheckSheetController::class, 'getCheckSheetHistory']);
    Route::post('checksheets/{checkSheet}/generate-next-round', [CheckSheetController::class, 'generateNextRoundChecksheet']);

    // CRUD APIs
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('current-locations', CurrentLocationController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('sub-categories', SubCategoryController::class);
    Route::apiResource('equipments', EquipmentController::class);
    Route::apiResource('activities', ActivityController::class);
    Route::apiResource('activity-items', ActivityItemController::class);
    Route::apiResource('users', UserController::class);
});
