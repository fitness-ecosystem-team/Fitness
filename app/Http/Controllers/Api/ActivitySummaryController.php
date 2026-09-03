<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivitySummary;
use Illuminate\Http\Request;

class ActivitySummaryController extends Controller
{
    public function today(Request $request)
    {
        $summary = $request->user()->activitySummaries()
            ->whereDate('date', today())
            ->first();

        if (! $summary) {
            return response()->json([
                'date'            => today()->toDateString(),
                'steps'           => 0,
                'calories_burned' => 0,
                'active_minutes'  => 0,
                'distance_km'     => 0,
                'water_ml'        => 0,
            ]);
        }

        return response()->json($summary);
    }

    public function history(Request $request)
    {
        $summaries = $request->user()->activitySummaries()
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return response()->json($summaries);
    }

    public function update(Request $request)
    {
        $request->validate([
            'date'            => 'required|date',
            'steps'           => 'nullable|integer|min:0',
            'calories_burned' => 'nullable|numeric|min:0',
            'active_minutes'  => 'nullable|integer|min:0',
            'distance_km'     => 'nullable|numeric|min:0',
            'water_ml'        => 'nullable|integer|min:0',
        ]);

        $summary = ActivitySummary::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'date'    => $request->date,
            ],
            $request->only([
                'steps',
                'calories_burned',
                'active_minutes',
                'distance_km',
                'water_ml',
            ])
        );

        return response()->json($summary);
    }
}
