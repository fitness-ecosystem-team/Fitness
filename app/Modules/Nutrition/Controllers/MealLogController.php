<?php

namespace App\Modules\Nutrition\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Nutrition\Models\Food;
use App\Modules\Nutrition\Models\MealLog;
use Illuminate\Http\Request;

class MealLogController extends Controller
{
    public function today(Request $request)
    {
        $logs = MealLog::where('user_id', $request->user()->id)
            ->whereDate('logged_at', today())
            ->with('food')
            ->get();

        $totals = [
            'calories' => $logs->sum('calories'),
            'protein'  => $logs->sum('protein'),
            'carbs'    => $logs->sum('carbs'),
            'fat'      => $logs->sum('fat'),
        ];

        return response()->json([
            'date'   => today()->toDateString(),
            'totals' => $totals,
            'logs'   => $logs,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'food_id'    => 'required|exists:foods,id',
            'meal_type'  => 'required|in:breakfast,lunch,dinner,snack',
            'quantity_g' => 'required|numeric|min:1',
            'logged_at'  => 'nullable|date',
        ]);

        $food       = Food::findOrFail($request->food_id);
        $multiplier = $request->quantity_g / 100;

        $log = MealLog::create([
            'user_id'    => $request->user()->id,
            'food_id'    => $food->id,
            'meal_type'  => $request->meal_type,
            'quantity_g' => $request->quantity_g,
            'calories'   => round($food->calories_per_100g * $multiplier, 2),
            'protein'    => round($food->protein_per_100g * $multiplier, 2),
            'carbs'      => round($food->carbs_per_100g * $multiplier, 2),
            'fat'        => round($food->fat_per_100g * $multiplier, 2),
            'logged_at'  => $request->logged_at ?? today(),
        ]);

        return response()->json($log->load('food'), 201);
    }

    public function destroy(Request $request, MealLog $mealLog)
    {
        if ($mealLog->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $mealLog->delete();

        return response()->json(['message' => 'Log deleted']);
    }
}
