<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Notification Controller for Admin
 */
class NotificationController extends Controller
{
    /**
     * عرض جميع الإشعارات
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(10);
        
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }
        
        return back();
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return back();
    }
}
