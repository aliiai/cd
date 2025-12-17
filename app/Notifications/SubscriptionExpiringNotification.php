<?php

namespace App\Notifications;

use App\Models\UserSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار قرب انتهاء الاشتراك
 */
class SubscriptionExpiringNotification extends Notification
{
    use Queueable;

    public function __construct(
        public UserSubscription $subscription,
        public int $daysRemaining
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiring',
            'title' => 'قرب انتهاء الاشتراك',
            'message' => "اشتراكك في باقة {$this->subscription->subscription->name} سينتهي خلال {$this->daysRemaining} يوم",
            'subscription_id' => $this->subscription->id,
            'subscription_name' => $this->subscription->subscription->name,
            'days_remaining' => $this->daysRemaining,
            'expires_at' => $this->subscription->expires_at?->format('Y-m-d'),
            'url' => route('owner.subscriptions.index'),
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
