<?php

namespace App\Notifications;

use App\Models\CollectionCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار إرسال رسالة
 */
class MessageSentNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CollectionCampaign $campaign,
        public int $recipientsCount,
        public string $channel
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $channelText = $this->channel === 'sms' ? 'SMS' : 'Email';
        
        return [
            'type' => 'message_sent',
            'title' => "تم إرسال رسالة {$channelText}",
            'message' => "تم إرسال رسالة {$channelText} إلى {$this->recipientsCount} مديون",
            'campaign_id' => $this->campaign->id,
            'campaign_number' => $this->campaign->campaign_number,
            'channel' => $this->channel,
            'recipients_count' => $this->recipientsCount,
            'url' => route('owner.collections.show', $this->campaign->id),
            'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
