<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار إنشاء مشرف جديد
 */
class AdminCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $admin
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'admin_created',
            'title' => 'تم إنشاء مشرف جديد',
            'message' => "تم إنشاء مشرف جديد: {$this->admin->name} ({$this->admin->email})",
            'admin_id' => $this->admin->id,
            'admin_name' => $this->admin->name,
            'url' => route('admin.admins.index'),
            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        ];
    }
}
