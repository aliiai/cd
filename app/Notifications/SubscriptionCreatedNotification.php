<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار إنشاء باقة جديدة للـ Admin
 */
class SubscriptionCreatedNotification extends Notification
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
            'type' => 'subscription_created',
            'title' => 'باقة جديدة',
            'message' => "تم إنشاء باقة جديدة: {$this->subscription->name}",
            'subscription_id' => $this->subscription->id,
            'subscription_name' => $this->subscription->name,
            'price' => $this->subscription->price,
            'url' => route('admin.subscriptions.show', $this->subscription->id),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ];
    }
}
