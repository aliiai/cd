<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Livewire Component لعرض الإشعارات في Dropdown
 */
class NotificationsDropdown extends Component
{
    public $notifications;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    /**
     * تحميل الإشعارات
     */
    public function loadNotifications()
    {
        $user = Auth::user();
        
        $this->notifications = $user
            ->notifications()
            ->latest()
            ->take(10)
            ->get();
        
        $this->unreadCount = $user->unreadNotifications()->count();
        
        // إعادة تحديث المكون
        $this->dispatch('notifications-updated');
    }

    /**
     * تحديد إشعار كمقروء
     */
    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->find($notificationId);
        
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    /**
     * تحديد جميع الإشعارات كمقروءة
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    /**
     * حذف إشعار
     */
    public function deleteNotification($notificationId)
    {
        Auth::user()->notifications()->find($notificationId)?->delete();
        $this->loadNotifications();
    }

    /**
     * الاستماع لإشعارات جديدة من Broadcasting
     */
    #[On('notification-received')]
    public function handleNotificationReceived()
    {
        $this->loadNotifications();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.notifications-dropdown');
    }
}
