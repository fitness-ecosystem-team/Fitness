<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->profile;

        if (! $profile) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json($profile);
    }

    public function update(Request $request)
    {
        $request->validate([
            'date_of_birth'  => 'nullable|date',
            'gender'         => 'nullable|in:male,female,other',
            'height_cm'      => 'nullable|numeric|min:50|max:300',
            'weight_kg'      => 'nullable|numeric|min:20|max:500',
            'fitness_goal'   => 'nullable|in:lose_weight,build_muscle,stay_fit,improve_endurance',
            'activity_level' => 'nullable|in:sedentary,light,moderate,active,very_active',
        ]);

        $profile = Profile::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->only([
                'date_of_birth',
                'gender',
                'height_cm',
                'weight_kg',
                'fitness_goal',
                'activity_level',
            ])
        );

        return response()->json($profile);
    }
}
