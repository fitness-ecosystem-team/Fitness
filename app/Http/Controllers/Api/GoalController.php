<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = $request->user()->goals()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->get();

        return response()->json($goals);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'     => 'required|in:fitness,nutrition,weight,sleep,mental,habits,hydration,steps,general',
            'title'        => 'required|string|max:255',
            'target_value' => 'nullable|numeric',
            'unit'         => 'nullable|string|max:50',
            'deadline'     => 'nullable|date',
        ]);

        $goal = $request->user()->goals()->create($request->only([
            'category',
            'title',
            'target_value',
            'unit',
            'deadline',
        ]));

        return response()->json($goal, 201);
    }

    public function update(Request $request, Goal $goal)
    {
        if ($goal->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $request->validate([
            'title'        => 'sometimes|string|max:255',
            'target_value' => 'nullable|numeric',
            'unit'         => 'nullable|string|max:50',
            'deadline'     => 'nullable|date',
            'status'       => 'sometimes|in:active,completed,abandoned',
        ]);

        $goal->update($request->only([
            'title',
            'target_value',
            'unit',
            'deadline',
            'status',
        ]));

        return response()->json($goal);
    }

    public function destroy(Request $request, Goal $goal)
    {
        if ($goal->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $goal->delete();

        return response()->json(['message' => 'Goal deleted']);
    }
}
