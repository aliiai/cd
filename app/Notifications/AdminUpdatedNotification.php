<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار تحديث مشرف
 */
class AdminUpdatedNotification extends Notification
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
            'type' => 'admin_updated',
            'title' => 'تم تحديث مشرف',
            'message' => "تم تحديث بيانات المشرف: {$this->admin->name} ({$this->admin->email})",
            'admin_id' => $this->admin->id,
            'admin_name' => $this->admin->name,
            'url' => route('admin.admins.index'),
            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ];
    }
}
