<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Models\BodyMeasurement;
use Illuminate\Http\Request;

class BodyMeasurementController extends Controller
{
    public function index(Request $request)
    {
        $measurements = $request->user()->bodyMeasurements()
            ->orderBy('measured_at', 'desc')
            ->get();

        return response()->json($measurements);
    }

    public function store(Request $request)
    {
        $request->validate([
            'measured_at'      => 'required|date',
            'weight_kg'        => 'nullable|numeric|min:1|max:500',
            'body_fat_percent' => 'nullable|numeric|min:1|max:100',
            'muscle_mass_kg'   => 'nullable|numeric|min:1|max:200',
            'waist_cm'         => 'nullable|numeric|min:1|max:300',
            'chest_cm'         => 'nullable|numeric|min:1|max:300',
            'hips_cm'          => 'nullable|numeric|min:1|max:300',
            'arms_cm'          => 'nullable|numeric|min:1|max:100',
            'thighs_cm'        => 'nullable|numeric|min:1|max:200',
            'neck_cm'          => 'nullable|numeric|min:1|max:100',
        ]);

        $measurement = $request->user()->bodyMeasurements()->create($request->only([
            'measured_at',
            'weight_kg',
            'body_fat_percent',
            'muscle_mass_kg',
            'waist_cm',
            'chest_cm',
            'hips_cm',
            'arms_cm',
            'thighs_cm',
            'neck_cm',
        ]));

        return response()->json($measurement, 201);
    }

    public function latest(Request $request)
    {
        $measurement = $request->user()->bodyMeasurements()
            ->orderBy('measured_at', 'desc')
            ->first();

        if (! $measurement) {
            return response()->json(['message' => 'No measurements found'], 404);
        }

        return response()->json($measurement);
    }
}
