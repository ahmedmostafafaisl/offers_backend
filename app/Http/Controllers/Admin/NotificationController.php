<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Helper\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Notification\NotificationResource;

class NotificationController extends Controller
{
    // GET /api/notifications
    public function index(Request $request)
    {
        $user = $request->user();

        $rows = $user->notifications()->get();

        return response()->json([
            'status' => true,
            'unread_count' => $user->unreadNotifications()->count(),
            'data' => NotificationResource::collection($rows),
            // 'meta' => [
            //     'current_page' => $rows->currentPage(),
            //     'last_page' => $rows->lastPage(),
            //     'per_page' => $rows->perPage(),
            //     'total' => $rows->total(),
            // ],
        ]);
    }

    // POST /api/notifications/read/{id}
    public function markRead(Request $request, string $id)
    {
        $n = $request->user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'Marked as read',
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    // POST /api/notifications/read-all
    public function markAllRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read',
            'unread_count' => 0,
        ]);
    }

    public function testPush(Request $request)
    {
        $request->validate([
            'token'   => 'nullable|string|max:512',
            'title'   => 'nullable|string|max:255',
            'message' => 'nullable|string|max:1000',
            'data'    => 'nullable|array',
        ]);

        $user = $request->user();

        $token = $request->input('token') ?: $user->fcm_token;

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'No FCM token found. Send token in request or set user.fcm_token.',
            ], 422);
        }

        $title = $request->input('title', 'FCM Test');
        $message = $request->input('message', 'Hello from Laravel');
        $data = $request->input('data', [
            'notificationType' => 'new_offer',
            'offer_id' => '1',
        ]);
        $data = array_map(fn($v) => (string)$v, $data);

        // ✅ Firebase requires all data values as strings
        $data = array_map(fn($v) => (string)$v, $data);

        try {
            $resp = FirebaseHelper::sendNotification($token, $title, $message, $data);

            return response()->json([
                'status' => true,
                'sent_to' => $token,
                'firebase_response' => $resp,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'FCM send failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
