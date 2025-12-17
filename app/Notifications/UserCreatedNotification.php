<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار إضافة مستخدم جديد للـ Admin
 */
class UserCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $role = $this->user->roles->first()?->name ?? 'غير محدد';
        $roleText = $role === 'owner' ? 'مالك' : ($role === 'admin' ? 'مدير' : 'مستخدم');
        
        return [
            'type' => 'user_created',
            'title' => 'مستخدم جديد',
            'message' => "تم إضافة مستخدم جديد: {$this->user->name} ({$roleText})",
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'role' => $role,
            'url' => route('admin.users.show', $this->user->id),
            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        ];
    }
}
