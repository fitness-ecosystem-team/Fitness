<?php

namespace App\Modules\Nutrition\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Nutrition\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $foods = Food::where(function ($q) use ($request) {
            $q->whereNull('user_id')
              ->orWhere('user_id', $request->user()->id);
        })
        ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
        ->limit(50)
        ->get();

        return response()->json($foods);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'brand'             => 'nullable|string|max:255',
            'calories_per_100g' => 'required|numeric|min:0',
            'protein_per_100g'  => 'nullable|numeric|min:0',
            'carbs_per_100g'    => 'nullable|numeric|min:0',
            'fat_per_100g'      => 'nullable|numeric|min:0',
            'fiber_per_100g'    => 'nullable|numeric|min:0',
            'sugar_per_100g'    => 'nullable|numeric|min:0',
        ]);

        $food = Food::create([
            ...$request->only([
                'name', 'brand',
                'calories_per_100g', 'protein_per_100g',
                'carbs_per_100g', 'fat_per_100g',
                'fiber_per_100g', 'sugar_per_100g',
            ]),
            'user_id'   => $request->user()->id,
            'is_custom' => true,
        ]);

        return response()->json($food, 201);
    }
}
