<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChatNotificationController extends Controller
{
    /**
     * Mark chat notifications as read for a specific chat room
     */
    public function markChatAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'chat_room_id' => 'required|integer|exists:chat_rooms,id'
        ]);

        $user = Auth::user();
        $chatRoomId = $request->chat_room_id;

        // Marca come lette tutte le notifiche chat per questa stanza
        $updated = Notification::where('user_id', $user->id)
            ->where('type', Notification::TYPE_CHAT_MESSAGE)
            ->whereJsonContains('data->chat_room_id', $chatRoomId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifiche chat marcate come lette',
            'updated_count' => $updated
        ]);
    }

    /**
     * Get unread chat notifications count
     */
    public function getUnreadCount(): JsonResponse
    {
        $user = Auth::user();
        $count = Notification::getUnreadChatCountForUser($user);

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }

    /**
     * Get chat notifications for a specific chat room
     */
    public function getChatNotifications(Request $request): JsonResponse
    {
        $request->validate([
            'chat_room_id' => 'required|integer|exists:chat_rooms,id'
        ]);

        $user = Auth::user();
        $chatRoomId = $request->chat_room_id;

        $notifications = Notification::where('user_id', $user->id)
            ->where('type', Notification::TYPE_CHAT_MESSAGE)
            ->whereJsonContains('data->chat_room_id', $chatRoomId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark all chat notifications as read
     */
    public function markAllChatAsRead(): JsonResponse
    {
        $user = Auth::user();

        $updated = Notification::where('user_id', $user->id)
            ->where('type', Notification::TYPE_CHAT_MESSAGE)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Tutte le notifiche chat marcate come lette',
            'updated_count' => $updated
        ]);
    }
}
