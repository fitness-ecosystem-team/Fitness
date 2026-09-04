<?php

namespace App\Core\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($notifications);
    }

    public function unread(Request $request)
    {
        $notifications = $request->user()->notifications()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'count'         => $notifications->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function markAllRead(Request $request)
    {
        $request->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}
