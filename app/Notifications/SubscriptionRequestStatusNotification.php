<?php

namespace App\Notifications;

use App\Models\SubscriptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار قبول/رفض طلب اشتراك
 */
class SubscriptionRequestStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SubscriptionRequest $request
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = $this->request->status === 'approved' ? 'تم قبول' : 'تم رفض';
        $message = $this->request->status === 'approved' 
            ? "تم قبول طلب اشتراكك في باقة {$this->request->subscription->name}"
            : "تم رفض طلب اشتراكك في باقة {$this->request->subscription->name}";

        return [
            'type' => 'subscription_request_status',
            'title' => "{$statusText} طلب الاشتراك",
            'message' => $message,
            'request_id' => $this->request->id,
            'subscription_id' => $this->request->subscription_id,
            'subscription_name' => $this->request->subscription->name,
            'status' => $this->request->status,
            'url' => route('owner.subscriptions.index'),
            'icon' => $this->request->status === 'approved' 
                ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                : 'M6 18L18 6M6 6l12 12',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
