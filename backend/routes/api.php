<?php

use App\Core\Controllers\AuthController;
use App\Core\Controllers\ProfileController;
use App\Core\Controllers\EntitlementController;
use App\Core\Controllers\ActivitySummaryController;
use App\Core\Controllers\ConsentController;
use App\Core\Controllers\ModuleController;
use App\Core\Controllers\UserModuleController;
use App\Core\Controllers\NotificationController;
use App\Core\Controllers\SettingController;
use App\Core\Controllers\StreakController;
use App\Core\Controllers\BodyMeasurementController;
use App\Core\Controllers\GoalController;
use App\Core\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

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

    Route::get('/modules',             [UserModuleController::class, 'index']);
    Route::post('/modules/activate',   [UserModuleController::class, 'activate']);
    Route::post('/modules/deactivate', [UserModuleController::class, 'deactivate']);

    Route::get('/consents',            [ConsentController::class, 'index']);
    Route::post('/consents/grant',     [ConsentController::class, 'grant']);
    Route::post('/consents/revoke',    [ConsentController::class, 'revoke']);
    Route::post('/consents/check',     [ConsentController::class, 'check']);

    // ── Module routes ────────────────────────────────────────────────────────
    // Each developer adds exactly ONE line below for their module.
    require __DIR__ . '/../app/Modules/Nutrition/routes.php';
    // require __DIR__ . '/../app/Modules/Activity/routes.php';
    // require __DIR__ . '/../app/Modules/Workout/routes.php';
    // require __DIR__ . '/../app/Modules/Sleep/routes.php';
    // require __DIR__ . '/../app/Modules/MentalWellness/routes.php';
    // require __DIR__ . '/../app/Modules/WeightBody/routes.php';
    // ─────────────────────────────────────────────────────────────────────────
});

}); // end v1
