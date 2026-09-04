<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show(Request $request)
    {
        $settings = Setting::firstOrCreate(
            ['user_id' => $request->user()->id],
            [
                'language'              => 'en',
                'weight_unit'           => 'kg',
                'distance_unit'         => 'km',
                'theme'                 => 'system',
                'notifications_enabled' => true,
                'workout_reminders'     => true,
                'nutrition_reminders'   => true,
                'sleep_reminders'       => true,
                'streak_alerts'         => true,
            ]
        );

        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $request->validate([
            'language'              => 'sometimes|in:en,ar,fr,es',
            'weight_unit'           => 'sometimes|in:kg,lbs',
            'distance_unit'         => 'sometimes|in:km,miles',
            'theme'                 => 'sometimes|in:light,dark,system',
            'notifications_enabled' => 'sometimes|boolean',
            'workout_reminders'     => 'sometimes|boolean',
            'nutrition_reminders'   => 'sometimes|boolean',
            'sleep_reminders'       => 'sometimes|boolean',
            'streak_alerts'         => 'sometimes|boolean',
        ]);

        $settings = Setting::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->only([
                'language',
                'weight_unit',
                'distance_unit',
                'theme',
                'notifications_enabled',
                'workout_reminders',
                'nutrition_reminders',
                'sleep_reminders',
                'streak_alerts',
            ])
        );

        return response()->json($settings);
    }
}
