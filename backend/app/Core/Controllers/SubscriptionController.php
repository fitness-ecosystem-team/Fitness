<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function show(Request $request)
    {
        $subscription = $request->user()->subscription;

        if (! $subscription) {
            return response()->json([
                'plan'   => 'free',
                'status' => 'active',
            ]);
        }

        return response()->json([
            'plan'       => $subscription->plan,
            'status'     => $subscription->status,
            'is_active'  => $subscription->isActive(),
            'expires_at' => $subscription->expires_at,
        ]);
    }
}
