<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        $count = AdminNotification::unread()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Get recent notifications
     */
    public function getRecent(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        $notifications = AdminNotification::latest()
            ->limit($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon,
                    'url' => $notification->url,
                    'is_read' => $notification->is_read,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'created_at' => $notification->created_at->format('Y-m-d H:i:s'),
                ];
            });
        
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => AdminNotification::unread()->count(),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'تم تعليم الإشعار كمقروء'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        AdminNotification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تعليم جميع الإشعارات كمقروءة'
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار'
        ]);
    }

    /**
     * Clear all read notifications
     */
    public function clearRead()
    {
        AdminNotification::read()->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'تم حذف جميع الإشعارات المقروءة'
        ]);
    }

    /**
     * Get notifications page
     */
    public function index(Request $request)
    {
        $query = AdminNotification::latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->paginate(20)->withQueryString();
        
        return view('admin.notifications.index', compact('notifications'));
    }
}
