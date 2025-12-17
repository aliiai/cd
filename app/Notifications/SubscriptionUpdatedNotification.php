<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار تحديث باقة للـ Admin
 */
class SubscriptionUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Subscription $subscription
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_updated',
            'title' => 'تم تحديث باقة',
            'message' => "تم تحديث باقة: {$this->subscription->name}",
            'subscription_id' => $this->subscription->id,
            'subscription_name' => $this->subscription->name,
            'url' => route('admin.subscriptions.show', $this->subscription->id),
            'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
        ];
    }
}
