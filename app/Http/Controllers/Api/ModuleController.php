<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function userContext(Request $request)
    {
        $user         = $request->user();
        $subscription = $user->subscription;
        $isPaid       = $subscription && $subscription->isActive() && $subscription->plan !== 'free';

        return response()->json([
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
            'profile'             => $user->profile,
            'goals'               => $user->goals()->where('status', 'active')->get(),
            'latest_measurement'  => $user->bodyMeasurements()->orderBy('measured_at', 'desc')->first(),
            'activity_today'      => $user->activitySummaries()->whereDate('date', today())->first(),
            'subscription' => [
                'plan'                => $subscription ? $subscription->plan : 'free',
                'is_active'           => $isPaid,
                'can_browse'          => true,
                'can_access_features' => $isPaid,
            ],
            'settings'            => $user->settings,
            'unread_notifications' => $user->notifications()->whereNull('read_at')->count(),
        ]);
    }
}
