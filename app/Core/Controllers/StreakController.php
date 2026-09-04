<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Models\Streak;
use Illuminate\Http\Request;

class StreakController extends Controller
{
    public function index(Request $request)
    {
        $streaks = $request->user()->streaks()->get();

        return response()->json($streaks);
    }

    public function record(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:100',
        ]);

        $streak = Streak::firstOrCreate(
            [
                'user_id' => $request->user()->id,
                'type'    => $request->type,
            ],
            [
                'current_streak'     => 0,
                'longest_streak'     => 0,
                'last_activity_date' => null,
            ]
        );

        $today     = today();
        $yesterday = today()->subDay();

        if ($streak->last_activity_date === null) {
            $streak->current_streak = 1;
        } elseif ($streak->last_activity_date->equalTo($today)) {
            // already recorded today, no change
        } elseif ($streak->last_activity_date->equalTo($yesterday)) {
            $streak->current_streak += 1;
        } else {
            // streak broken
            $streak->current_streak = 1;
        }

        if ($streak->current_streak > $streak->longest_streak) {
            $streak->longest_streak = $streak->current_streak;
        }

        $streak->last_activity_date = $today;
        $streak->save();

        return response()->json($streak);
    }
}
