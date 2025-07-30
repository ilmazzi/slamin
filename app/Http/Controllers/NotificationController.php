<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display user's notifications
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Notification::where('user_id', $user->id);

        // Filter by read status
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        $notifications = $query->recent()
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        $unreadCount = Notification::getUnreadCountForUser($user);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Get notifications for dropdown/sidebar
     */
    public function dropdown(): JsonResponse
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
                                   ->unread()
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get()
                                   ->map(function ($notification) {
                                       // Get sender avatar based on notification type and data
                                       $senderAvatar = $this->getSenderAvatar($notification);

                                       return [
                                           'id' => $notification->id,
                                           'title' => $notification->title ?? '',
                                           'message' => $notification->message ?? '',
                                           'type' => $notification->type ?? '',
                                           'icon' => $notification->icon ?? 'ph-bell',
                                           'color' => $notification->color ?? 'primary',
                                           'priority_badge' => $notification->priority_badge ?? '',
                                           'action_url' => $notification->action_url ?? '',
                                           'action_text' => $notification->action_text ?? '',
                                           'data' => $notification->data ?? [],
                                           'created_at' => $notification->created_at ? $notification->created_at->diffForHumans() : 'Ora',
                                           'is_read' => $notification->is_read ?? false,
                                           'sender_avatar' => $senderAvatar,
                                       ];
                                   });

        $unreadCount = Notification::getUnreadCountForUser($user);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'has_more' => $unreadCount > 10,
        ]);
    }

        /**
     * Get sender avatar for notification
     */
    private function getSenderAvatar(Notification $notification): ?string
    {
        $data = $notification->data ?? [];

        // Group role changed notification
        if ($notification->type === 'group_role_changed' && isset($data['changed_by_id'])) {
            $user = \App\Models\User::find($data['changed_by_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Group invitation notification
        if ($notification->type === 'group_invitation' && isset($data['invited_by'])) {
            $user = \App\Models\User::find($data['invited_by']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Group invitation response notification
        if (in_array($notification->type, ['group_invitation_accepted', 'group_invitation_declined']) && isset($data['user_id'])) {
            $user = \App\Models\User::find($data['user_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Event invitation notification - avatar dell'organizzatore dell'evento
        if ($notification->type === 'event_invitation' && isset($data['invitation_id'])) {
            $invitation = \App\Models\EventInvitation::find($data['invitation_id']);
            if ($invitation && $invitation->event) {
                return \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->event->user);
            }
        }

        // Event invitation response notification - avatar di chi ha risposto
        if (in_array($notification->type, ['invitation_accepted', 'invitation_declined']) && isset($data['invited_user_id'])) {
            $user = \App\Models\User::find($data['invited_user_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Event update notification - avatar dell'organizzatore dell'evento
        if ($notification->type === 'event_update' && isset($data['event_id'])) {
            $event = \App\Models\Event::find($data['event_id']);
            if ($event) {
                return \App\Helpers\AvatarHelper::getUserAvatarUrl($event->user);
            }
        }

        // Event cancelled notification - avatar dell'organizzatore dell'evento
        if ($notification->type === 'event_cancelled' && isset($data['event_id'])) {
            $event = \App\Models\Event::find($data['event_id']);
            if ($event) {
                return \App\Helpers\AvatarHelper::getUserAvatarUrl($event->user);
            }
        }

        // Event reminder notification - avatar dell'organizzatore dell'evento
        if ($notification->type === 'event_reminder' && isset($data['event_id'])) {
            $event = \App\Models\Event::find($data['event_id']);
            if ($event) {
                return \App\Helpers\AvatarHelper::getUserAvatarUrl($event->user);
            }
        }

                // Group join request notification - avatar di chi ha fatto la richiesta
        if ($notification->type === 'group_join_request' && isset($data['user_id'])) {
            $user = \App\Models\User::find($data['user_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Group join request response notification - avatar di chi ha processato la richiesta
        if (in_array($notification->type, ['group_join_request_accepted', 'group_join_request_declined']) && isset($data['processed_by_id'])) {
            $user = \App\Models\User::find($data['processed_by_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        // Group member joined/left notification
        if (in_array($notification->type, ['group_member_joined', 'group_member_left']) && isset($data['new_member_id'])) {
            $user = \App\Models\User::find($data['new_member_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        if (in_array($notification->type, ['group_member_joined', 'group_member_left']) && isset($data['left_member_id'])) {
            $user = \App\Models\User::find($data['left_member_id']);
            return $user ? \App\Helpers\AvatarHelper::getUserAvatarUrl($user) : null;
        }

        return null;
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifica segnata come letta.',
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        $notification->markAsUnread();

        return response()->json([
            'success' => true,
            'message' => 'Notifica segnata come non letta.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();
        $count = Notification::markAllAsReadForUser($user);

        return response()->json([
            'success' => true,
            'message' => "$count notifiche segnate come lette.",
            'count' => $count,
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        // Check if user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato.');
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifica eliminata.',
        ]);
    }

    /**
     * Bulk actions on notifications
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:mark_read,mark_unread,delete',
            'notification_ids' => 'required|array|min:1',
            'notification_ids.*' => 'exists:notifications,id',
        ]);

        $user = Auth::user();

        $notifications = Notification::whereIn('id', $validated['notification_ids'])
                                   ->where('user_id', $user->id)
                                   ->get();

        $successCount = 0;

        foreach ($notifications as $notification) {
            try {
                switch ($validated['action']) {
                    case 'mark_read':
                        $notification->markAsRead();
                        $successCount++;
                        break;

                    case 'mark_unread':
                        $notification->markAsUnread();
                        $successCount++;
                        break;

                    case 'delete':
                        $notification->delete();
                        $successCount++;
                        break;
                }
            } catch (\Exception $e) {
                // Log error but continue with other notifications
                continue;
            }
        }

        $action = match ($validated['action']) {
            'mark_read' => 'segnate come lette',
            'mark_unread' => 'segnate come non lette',
            'delete' => 'eliminate',
        };

        return response()->json([
            'success' => true,
            'message' => "$successCount notifiche $action.",
            'processed' => $successCount,
        ]);
    }

    /**
     * Get notification statistics
     */
    public function statistics(): JsonResponse
    {
        $user = Auth::user();

        $stats = [
            'total' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->unread()->count(),
            'today' => Notification::where('user_id', $user->id)
                                 ->whereDate('created_at', today())
                                 ->count(),
            'this_week' => Notification::where('user_id', $user->id)
                                     ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                                     ->count(),
        ];

        // Get notifications by type
        $typeStats = Notification::where('user_id', $user->id)
                                ->selectRaw('type, COUNT(*) as count')
                                ->groupBy('type')
                                ->orderBy('count', 'desc')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item->type => $item->count];
                                });

        $stats['by_type'] = $typeStats;

        // Get notifications by priority
        $priorityStats = Notification::where('user_id', $user->id)
                                   ->selectRaw('priority, COUNT(*) as count')
                                   ->groupBy('priority')
                                   ->orderBy('count', 'desc')
                                   ->get()
                                   ->mapWithKeys(function ($item) {
                                       return [$item->priority => $item->count];
                                   });

        $stats['by_priority'] = $priorityStats;

        return response()->json($stats);
    }

    /**
     * Clean old read notifications
     */
    public function cleanup(): JsonResponse
    {
        $user = Auth::user();

        // Delete read notifications older than 30 days
        $count = Notification::where('user_id', $user->id)
                            ->where('is_read', true)
                            ->where('created_at', '<', now()->subDays(30))
                            ->delete();

        return response()->json([
            'success' => true,
            'message' => "$count notifiche vecchie eliminate.",
            'cleaned' => $count,
        ]);
    }

    /**
     * Get real-time notification updates
     */
    public function realtime(): JsonResponse
    {
        $user = Auth::user();

        // Get latest notifications (last 5 minutes)
        $notifications = Notification::where('user_id', $user->id)
                                   ->where('created_at', '>=', now()->subMinutes(5))
                                   ->orderBy('created_at', 'desc')
                                   ->get()
                                   ->map(function ($notification) {
                                       return [
                                           'id' => $notification->id,
                                           'title' => $notification->title,
                                           'message' => $notification->message,
                                           'type' => $notification->type,
                                           'icon' => $notification->icon,
                                           'color' => $notification->color,
                                           'priority' => $notification->priority,
                                           'action_url' => $notification->action_url,
                                           'created_at' => $notification->created_at->toISOString(),
                                           'is_read' => $notification->is_read,
                                       ];
                                   });

        $unreadCount = Notification::getUnreadCountForUser($user);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Test notification (development only)
     */
    public function test(): JsonResponse
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $user = Auth::user();

        Notification::create([
            'user_id' => $user->id,
            'type' => 'test',
            'title' => 'Notifica di Test',
            'message' => 'Questa è una notifica di test generata alle ' . now()->format('H:i:s'),
            'data' => ['test' => true],
            'priority' => Notification::PRIORITY_NORMAL,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifica di test creata.',
        ]);
    }
}
