<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EntitlementController extends Controller
{
    public function show(Request $request)
    {
        $user         = $request->user();
        $subscription = $user->subscription;

        $isPaid = $subscription && $subscription->isActive() && $subscription->plan !== 'free';

        return response()->json([
            'can_browse'          => true,
            'can_access_features' => $isPaid,
            'plan'                => $subscription ? $subscription->plan : 'free',
        ]);
    }
}
