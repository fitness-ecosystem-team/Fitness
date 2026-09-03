<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EntitlementController;
use App\Http\Controllers\Api\ActivitySummaryController;
use App\Http\Controllers\Api\ModuleController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StreakController;
use App\Http\Controllers\Api\BodyMeasurementController;
use App\Http\Controllers\Api\GoalController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile',      [ProfileController::class, 'show']);
    Route::post('/profile',     [ProfileController::class, 'update']);

    Route::get('/subscription',  [SubscriptionController::class, 'show']);
    Route::get('/entitlements',  [EntitlementController::class, 'show']);

    Route::get('/goals',           [GoalController::class, 'index']);
    Route::post('/goals',          [GoalController::class, 'store']);
    Route::put('/goals/{goal}',    [GoalController::class, 'update']);
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy']);

    Route::get('/measurements',         [BodyMeasurementController::class, 'index']);
    Route::post('/measurements',        [BodyMeasurementController::class, 'store']);
    Route::get('/measurements/latest',  [BodyMeasurementController::class, 'latest']);

    Route::get('/activity/today',   [ActivitySummaryController::class, 'today']);
    Route::get('/activity/history', [ActivitySummaryController::class, 'history']);
    Route::post('/activity',        [ActivitySummaryController::class, 'update']);

    Route::get('/streaks',          [StreakController::class, 'index']);
    Route::post('/streaks/record',  [StreakController::class, 'record']);

    Route::get('/notifications',              [NotificationController::class, 'index']);
    Route::get('/notifications/unread',       [NotificationController::class, 'unread']);
    Route::post('/notifications/read-all',    [NotificationController::class, 'markAllRead']);
    Route::post('/notifications/{id}/read',   [NotificationController::class, 'markRead']);

    Route::get('/settings',  [SettingController::class, 'show']);
    Route::post('/settings', [SettingController::class, 'update']);

    Route::get('/module/user-context', [ModuleController::class, 'userContext']);
});
