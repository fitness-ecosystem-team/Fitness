<?php

namespace App\Modules\Nutrition\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Nutrition\Models\WaterLog;
use Illuminate\Http\Request;

class WaterLogController extends Controller
{
    public function today(Request $request)
    {
        $total = WaterLog::where('user_id', $request->user()->id)
            ->whereDate('logged_at', today())
            ->sum('amount_ml');

        return response()->json([
            'date'      => today()->toDateString(),
            'total_ml'  => $total,
            'total_L'   => round($total / 1000, 2),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount_ml' => 'required|integer|min:1',
            'logged_at' => 'nullable|date',
        ]);

        $log = WaterLog::create([
            'user_id'   => $request->user()->id,
            'amount_ml' => $request->amount_ml,
            'logged_at' => $request->logged_at ?? today(),
        ]);

        return response()->json($log, 201);
    }
}
