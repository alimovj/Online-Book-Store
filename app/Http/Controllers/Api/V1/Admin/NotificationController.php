<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Barcha notifications (o'qilgan va o'qilmagan)
    public function index(Request $request): 
    {
        $type = $request->query('type', 'all'); // default 'all'
        $admin = $request->user();

        $notifications = match ($type) {
            'unread' => $admin->unreadNotifications,
            'read'   => $admin->readNotifications,
            default  => $admin->notifications,
        };

        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);
    }

    // Bitta notificationni o‘qilgan qilish
    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        if ($notification->read_at) {
            return response()->json([
                'message' => 'Notification already marked as read',
            ]);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }

    // Barcha notificationlarni o‘qilgan qilish
    public function markAllAsRead(): JsonResponse
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
