<?php

namespace App\Console\Commands;

use App\Models\UserSubscription;
use App\Notifications\SubscriptionExpiringNotification;
use Illuminate\Console\Command;

/**
 * Command للتحقق من الاشتراكات القريبة من الانتهاء
 */
class CheckExpiringSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expiring';
    protected $description = 'Check for expiring subscriptions and send notifications';

    public function handle()
    {
        // البحث عن اشتراكات ستنتهي خلال 7 أيام
        $expiringSubscriptions = UserSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->with(['user', 'subscription'])
            ->get();

        foreach ($expiringSubscriptions as $subscription) {
            $daysRemaining = now()->diffInDays($subscription->expires_at);
            
            // إرسال إشعار فقط إذا لم يتم إرساله من قبل
            $hasNotification = $subscription->user->notifications()
                ->where('type', SubscriptionExpiringNotification::class)
                ->where('data->subscription_id', $subscription->id)
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if (!$hasNotification) {
                $subscription->user->notify(new SubscriptionExpiringNotification(
                    $subscription,
                    $daysRemaining
                ));
            }
        }

        $this->info("Checked {$expiringSubscriptions->count()} expiring subscriptions.");
    }
}

