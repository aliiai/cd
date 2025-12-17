<?php

namespace App\Notifications;

use App\Models\Debtor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

/**
 * إشعار تغيير حالة دين
 */
class DebtorStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Debtor $debtor,
        public string $oldStatus,
        public string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusText = match($this->newStatus) {
            'new' => 'جديد',
            'contacted' => 'تم التواصل',
            'promise_to_pay' => 'وعد بالدفع',
            'paid' => 'مدفوع',
            'overdue' => 'متأخر',
            'failed' => 'فشل',
            default => 'غير محدد',
        };

        return [
            'type' => 'debtor_status_changed',
            'title' => 'تم تغيير حالة الدين',
            'message' => "تم تغيير حالة دين {$this->debtor->name} إلى: {$statusText}",
            'debtor_id' => $this->debtor->id,
            'debtor_name' => $this->debtor->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'status_text' => $statusText,
            'url' => route('owner.debtors.index', ['status' => $this->newStatus]),
            'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

