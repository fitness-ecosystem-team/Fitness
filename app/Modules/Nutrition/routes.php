<?php

use App\Modules\Nutrition\Controllers\FoodController;
use App\Modules\Nutrition\Controllers\MealLogController;
use App\Modules\Nutrition\Controllers\WaterLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('nutrition')->group(function () {
    // Foods
    Route::get('foods', [FoodController::class, 'index']);
    Route::post('foods', [FoodController::class, 'store']);

    // Meal logs
    Route::get('meals/today', [MealLogController::class, 'today']);
    Route::post('meals', [MealLogController::class, 'store']);
    Route::delete('meals/{mealLog}', [MealLogController::class, 'destroy']);

    // Water logs
    Route::get('water/today', [WaterLogController::class, 'today']);
    Route::post('water', [WaterLogController::class, 'store']);
});
