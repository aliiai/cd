<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Notifications\CampaignCreatedNotification;
use App\Notifications\MessageSentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Collection Controller for Owner
 * 
 * يدير حملات التحصيل (Collection Campaigns)
 */
class CollectionController extends Controller
{
    /**
     * عرض صفحة حملات التحصيل
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المديونين للمالك الحالي
        $debtors = Debtor::where('owner_id', Auth::id())->get();
        
        // جلب جميع الحملات السابقة للمالك الحالي
        $campaigns = CollectionCampaign::where('owner_id', Auth::id())
            ->with('debtors')
            ->latest()
            ->get();
        
        // معلومات الاشتراك والاستهلاك
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptionInfo = null;
        
        if ($activeSubscription) {
            $subscription = $activeSubscription->subscription;
            
            // حساب عدد الرسائل المرسلة
            $messagesSent = DB::table('collection_campaign_clients')
                ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                ->where('collection_campaigns.owner_id', Auth::id())
                ->where('collection_campaign_clients.status', 'sent')
                ->count();
            
            $maxMessages = $subscription->max_messages ?? 0;
            $messagesRemaining = $maxMessages > 0 ? max(0, $maxMessages - $messagesSent) : null;
            $messagesUsage = $maxMessages > 0 ? ($messagesSent / $maxMessages) * 100 : 0;
            
            $subscriptionInfo = [
                'subscription_name' => $subscription->name,
                'max_messages' => $maxMessages,
                'messages_sent' => $messagesSent,
                'messages_remaining' => $messagesRemaining,
                'messages_usage' => $messagesUsage,
            ];
        }
        
        return view('owner.collections.index', compact('debtors', 'campaigns', 'subscriptionInfo'));
    }

    /**
     * إنشاء حملة تحصيل جديدة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'exists:clients,id',
            'channel' => 'required|in:sms,email',
            'template' => 'nullable|string',
            'message' => 'required|string|min:10',
            'send_type' => 'required|in:now,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // التحقق من أن المديونين يخصون المالك الحالي
        $debtors = Debtor::where('owner_id', Auth::id())
            ->whereIn('id', $validated['client_ids'])
            ->get();

        if ($debtors->count() !== count($validated['client_ids'])) {
            return back()->with('error', 'بعض المديونين المحددين غير موجودين أو لا يخصونك.');
        }

        // التحقق من حدود الاشتراك - عدد الرسائل
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        
        if (!$activeSubscription) {
            return redirect()->route('owner.subscriptions.index')
                ->with('error', 'لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات أولاً.');
        }

        $subscription = $activeSubscription->subscription;
        $maxMessages = $subscription->max_messages ?? 0;
        
        // حساب عدد الرسائل المرسلة حتى الآن
        $messagesSent = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', Auth::id())
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        // عدد الرسائل المطلوبة في هذه الحملة
        $messagesNeeded = $debtors->count();
        
        // التحقق من تجاوز الحد
        if ($maxMessages > 0 && ($messagesSent + $messagesNeeded) > $maxMessages) {
            $remaining = $maxMessages - $messagesSent;
            return redirect()->route('owner.subscriptions.index')
                ->with('error', "لقد تجاوزت الحد المسموح للرسائل! الحد المسموح: {$maxMessages} رسالة، المستخدم: {$messagesSent}، المتبقي: {$remaining}. يرجى ترقية اشتراكك لإرسال المزيد من الرسائل.");
        }

        // تحديد حالة الحملة
        $status = $validated['send_type'] === 'now' ? 'sent' : 'scheduled';

        // إنشاء الحملة
        $campaign = CollectionCampaign::create([
            'owner_id' => Auth::id(),
            'channel' => $validated['channel'],
            'template' => $validated['template'] ?? null,
            'message' => $validated['message'],
            'send_type' => $validated['send_type'],
            'scheduled_at' => $validated['send_type'] === 'scheduled' ? $validated['scheduled_at'] : null,
            'status' => $status,
            'total_recipients' => $debtors->count(),
            'sent_count' => $status === 'sent' ? $debtors->count() : 0,
        ]);

        // ربط المديونين بالحملة
        $campaign->debtors()->attach($validated['client_ids']);

        // إرسال إشعار إنشاء حملة
        Auth::user()->notify(new CampaignCreatedNotification($campaign));

        // في حالة الإرسال الفوري، تحديث حالة المديونين في pivot
        if ($status === 'sent') {
            foreach ($validated['client_ids'] as $clientId) {
                $campaign->debtors()->updateExistingPivot($clientId, [
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
            
            // إرسال إشعار إرسال الرسالة
            Auth::user()->notify(new MessageSentNotification(
                $campaign,
                $debtors->count(),
                $validated['channel']
            ));
        }

        $message = $status === 'sent' ? 'تم إرسال الحملة بنجاح.' : 'تم جدولة الحملة بنجاح.';
        
        return redirect()->route('owner.collections.index')
            ->with('success', $message);
    }

    /**
     * عرض تفاصيل حملة معينة
     * 
     * @param CollectionCampaign $campaign
     * @return \Illuminate\View\View
     */
    public function show(CollectionCampaign $campaign)
    {
        // التحقق من أن الحملة تخص المالك الحالي
        if ($campaign->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه الحملة.');
        }

        $campaign->load('debtors');
        
        return view('owner.collections.show', compact('campaign'));
    }
}

