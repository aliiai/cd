<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار تغيير حالة مشرف
 */
class AdminStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $admin,
        public bool $isActive
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = $this->isActive ? 'مفعل' : 'موقوف';
        
        return [
            'type' => 'admin_status_changed',
            'title' => 'تغيير حالة مشرف',
            'message' => "تم {$status} حساب المشرف: {$this->admin->name} ({$this->admin->email})",
            'admin_id' => $this->admin->id,
            'admin_name' => $this->admin->name,
            'is_active' => $this->isActive,
            'status' => $status,
            'url' => route('admin.admins.index'),
            'icon' => $this->isActive ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
        ];
    }
}
