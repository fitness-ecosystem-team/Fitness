<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserConsent;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    public function index(Request $request)
    {
        $consents = $request->user()->userConsents()->get();

        return response()->json($consents);
    }

    public function grant(Request $request)
    {
        $request->validate([
            'module'    => 'required|string|max:100',
            'data_type' => 'required|in:body_data,activity_data,sleep_data,nutrition_data,mental_data,reproductive_data,location_data,connected_device_data',
        ]);

        $consent = UserConsent::updateOrCreate(
            [
                'user_id'   => $request->user()->id,
                'module'    => $request->module,
                'data_type' => $request->data_type,
            ],
            [
                'granted'    => true,
                'granted_at' => now(),
                'revoked_at' => null,
            ]
        );

        return response()->json($consent);
    }

    public function revoke(Request $request)
    {
        $request->validate([
            'module'    => 'required|string|max:100',
            'data_type' => 'required|string',
        ]);

        $consent = $request->user()->userConsents()
            ->where('module', $request->module)
            ->where('data_type', $request->data_type)
            ->first();

        if ($consent) {
            $consent->update([
                'granted'    => false,
                'revoked_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Consent revoked']);
    }

    public function check(Request $request)
    {
        $request->validate([
            'module'    => 'required|string',
            'data_type' => 'required|string',
        ]);

        $consent = $request->user()->userConsents()
            ->where('module', $request->module)
            ->where('data_type', $request->data_type)
            ->where('granted', true)
            ->exists();

        return response()->json(['granted' => $consent]);
    }
}
