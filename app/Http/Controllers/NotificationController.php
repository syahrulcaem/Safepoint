<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Get recent notifications for the current user
     */
    public function index(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $notifications = $this->notificationService->getRecentNotifications(
            Auth::user()->id,
            $limit
        );

        $unreadCount = $this->notificationService->getUnreadCount(Auth::user()->id);

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'is_read' => $notification->isRead(),
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => $notification->created_at->diffForHumans(),
                    'case' => $notification->case ? [
                        'id' => $notification->case->id,
                        'short_id' => $notification->case->short_id,
                        'status' => $notification->case->status,
                        'category' => $notification->case->category,
                        'location' => $notification->case->location
                    ] : null
                ];
            }),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $success = $this->notificationService->markAsRead($id, Auth::user()->id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found or already read'
        ], 404);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        $count = $this->notificationService->markAllAsRead(Auth::user()->id);

        return response()->json([
            'success' => true,
            'message' => "Marked {$count} notifications as read"
        ]);
    }

    /**
     * Get unread notification count only
     */
    public function unreadCount(): JsonResponse
    {
        $count = $this->notificationService->getUnreadCount(Auth::user()->id);

        return response()->json([
            'success' => true,
            'unread_count' => $count
        ]);
    }
}
