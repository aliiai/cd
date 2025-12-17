<?php

namespace App\Notifications;

use App\Models\CollectionCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار إنشاء حملة تحصيل
 */
class CampaignCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CollectionCampaign $campaign
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $channelText = $this->campaign->channel === 'sms' ? 'SMS' : 'Email';
        $statusText = match($this->campaign->status) {
            'sent' => 'تم الإرسال',
            'scheduled' => 'مجدول',
            'failed' => 'فشل',
            'cancelled' => 'ملغي',
            default => 'غير محدد',
        };

        return [
            'type' => 'campaign_created',
            'title' => 'تم إنشاء حملة تحصيل جديدة',
            'message' => "تم إنشاء حملة {$channelText} جديدة - {$statusText}",
            'campaign_id' => $this->campaign->id,
            'campaign_number' => $this->campaign->campaign_number,
            'channel' => $this->campaign->channel,
            'status' => $this->campaign->status,
            'recipients_count' => $this->campaign->total_recipients,
            'url' => route('owner.collections.show', $this->campaign->id),
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
