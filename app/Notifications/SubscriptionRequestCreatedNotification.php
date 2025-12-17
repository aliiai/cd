<?php

namespace App\Notifications;

use App\Models\SubscriptionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * إشعار طلب اشتراك جديد للـ Admin
 */
class SubscriptionRequestCreatedNotification extends Notification
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
        return [
            'type' => 'subscription_request_created',
            'title' => 'طلب اشتراك جديد',
            'message' => "طلب جديد من {$this->request->user->name} للاشتراك في باقة {$this->request->subscription->name}",
            'request_id' => $this->request->id,
            'user_id' => $this->request->user_id,
            'user_name' => $this->request->user->name,
            'subscription_id' => $this->request->subscription_id,
            'subscription_name' => $this->request->subscription->name,
            'url' => route('admin.subscription-requests.show', $this->request->id),
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ];
    }
}
