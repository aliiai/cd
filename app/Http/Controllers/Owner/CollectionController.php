<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Notifications\CampaignCreatedNotification;
use App\Notifications\MessageSentNotification;
use App\Services\FourJawalyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    public function index(Request $request)
    {
        // جلب جميع المديونين للمالك الحالي
        $debtors = Debtor::where('owner_id', Auth::id())->get();
        
        // بناء استعلام الحملات
        $query = CollectionCampaign::where('owner_id', Auth::id())
            ->with('debtors');
        
        // فلترة حسب نوع الإرسال
        if ($request->filled('send_type') && $request->send_type !== 'all') {
            $query->where('send_type', $request->send_type);
        }
        
        // فلترة حسب القناة
        if ($request->filled('channel') && $request->channel !== 'all') {
            $query->where('channel', $request->channel);
        }
        
        // فلترة حسب الحالة
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // جلب الحملات مع pagination
        $campaigns = $query->latest()->paginate(10)->appends($request->query());
        
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

        // في حالة الإرسال الفوري، إرسال الرسائل فعلياً
        if ($status === 'sent') {
            $sentCount = 0;
            $failedCount = 0;

            if ($validated['channel'] === 'sms') {
                // إرسال SMS عبر 4jawaly
                $smsService = new FourJawalyService();
                
                foreach ($debtors as $debtor) {
                    try {
                        // استبدال placeholders في الرسالة
                        $message = $validated['message'];
                        
                        // استبدال {{name}} أو @{{name}} باسم المديون
                        $message = str_replace(['{{name}}', '@{{name}}'], $debtor->name ?? 'العميل', $message);
                        
                        // استبدال {{amount}} أو @{{amount}} بمبلغ الدين
                        $debtAmount = $debtor->has_installments 
                            ? number_format($debtor->remaining_amount, 2)
                            : number_format($debtor->debt_amount, 2);
                        $message = str_replace(['{{amount}}', '@{{amount}}'], $debtAmount, $message);
                        
                        // استبدال {{link}} أو @{{link}} برابط الدفع إن وجد
                        $paymentLink = $debtor->payment_link ?? 'غير متوفر';
                        $message = str_replace(['{{link}}', '@{{link}}'], $paymentLink, $message);
                        
                        // تسجيل الرسالة المعدلة للتأكد من الاستبدال
                        Log::debug('SMS message after placeholder replacement', [
                            'debtor_id' => $debtor->id,
                            'debtor_name' => $debtor->name,
                            'debt_amount' => $debtAmount,
                            'original_message' => $validated['message'],
                            'final_message' => $message,
                        ]);
                        
                        // إرسال SMS مع metadata للتسجيل
                        $result = $smsService->sendSMS($debtor->phone, $message, [
                            'event_type' => 'collection_campaign',
                            'entity_type' => CollectionCampaign::class,
                            'entity_id' => $campaign->id,
                        ]);
                        
                        // تحديث حالة الإرسال في pivot table
                        $errorMessage = null;
                        if (!$result['success']) {
                            // استخدام error بدلاً من message لأن message قد يكون مضللاً
                            $errorMessage = $result['error'] ?? 
                                          (isset($result['message']) && stripos($result['message'], 'فشل') !== false ? $result['message'] : null) ??
                                          ($result['message'] ?? 'فشل الإرسال');
                            
                            // إذا كانت الرسالة تقول "نجح" أو "حفظ" لكن success = false، نستخدم رسالة افتراضية
                            if (stripos($errorMessage, 'نجح') !== false || 
                                stripos($errorMessage, 'حفظ') !== false || 
                                stripos($errorMessage, 'success') !== false) {
                                $errorMessage = 'فشل إرسال الرسالة - استجابة API غير واضحة';
                            }
                        }
                        
                        $campaign->debtors()->updateExistingPivot($debtor->id, [
                            'status' => $result['success'] ? 'sent' : 'failed',
                            'sent_at' => $result['success'] ? now() : null,
                            'error_message' => $errorMessage,
                        ]);
                        
                        // تحديث عداد الحملة
                        if ($result['success']) {
                            $sentCount++;
                        } else {
                            $failedCount++;
                        }
                    } catch (\Exception $e) {
                        // في حالة الخطأ
                        $campaign->debtors()->updateExistingPivot($debtor->id, [
                            'status' => 'failed',
                            'error_message' => $e->getMessage(),
                        ]);
                        $failedCount++;
                        
                        Log::error('Failed to send SMS to debtor: ' . $debtor->id, [
                            'phone' => $debtor->phone,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // تحديث إحصائيات الحملة
                $campaign->update([
                    'sent_count' => $sentCount,
                    'failed_count' => $failedCount,
                ]);
            } else {
                // Email - يمكن إضافة منطق إرسال Email لاحقاً
                foreach ($validated['client_ids'] as $clientId) {
                    $campaign->debtors()->updateExistingPivot($clientId, [
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                }
                $sentCount = $debtors->count();
            }
            
            // إرسال إشعار إرسال الرسالة
            Auth::user()->notify(new MessageSentNotification(
                $campaign,
                $sentCount,
                $validated['channel']
            ));
            
            // تحديد رسالة النتيجة بناءً على حالة الإرسال
            $totalCount = $sentCount + $failedCount;
            
            if ($failedCount > 0 && $sentCount > 0) {
                // نجح بعض الرسائل وفشل البعض
                $message = "تم إرسال {$sentCount} من أصل {$totalCount} رسالة بنجاح. فشل إرسال {$failedCount} رسالة.";
                
                // جمع أسباب الفشل من pivot table
                $failedDebtors = $campaign->debtors()
                    ->wherePivot('status', 'failed')
                    ->get();
                
                $errorReasons = [];
                foreach ($failedDebtors as $debtor) {
                    $errorMsg = $debtor->pivot->error_message;
                    if ($errorMsg && !in_array($errorMsg, $errorReasons)) {
                        $errorReasons[] = $errorMsg;
                    }
                }
                
                if (!empty($errorReasons)) {
                    $mainReason = $errorReasons[0];
                    // تبسيط رسالة الخطأ
                    if (stripos($mainReason, 'التوكن') !== false || stripos($mainReason, 'token') !== false) {
                        $mainReason = 'بيانات API غير صحيحة أو منتهية الصلاحية';
                    }
                    $message .= " السبب: " . $mainReason;
                }
                
                return redirect()->route('owner.collections.index')
                    ->with('warning', $message);
            } elseif ($failedCount > 0 && $sentCount === 0) {
                // فشلت جميع الرسائل
                $failedDebtors = $campaign->debtors()
                    ->wherePivot('status', 'failed')
                    ->get();
                
                $errorReasons = [];
                foreach ($failedDebtors as $debtor) {
                    $errorMsg = $debtor->pivot->error_message;
                    if ($errorMsg && !in_array($errorMsg, $errorReasons)) {
                        $errorReasons[] = $errorMsg;
                    }
                }
                
                $mainReason = !empty($errorReasons) ? $errorReasons[0] : 'فشل إرسال جميع الرسائل';
                
                // تبسيط رسالة الخطأ
                if (stripos($mainReason, 'التوكن') !== false || stripos($mainReason, 'token') !== false || stripos($mainReason, 'منتهى') !== false) {
                    $mainReason = 'بيانات API غير صحيحة أو منتهية الصلاحية. يرجى التحقق من إعدادات API في ملف .env';
                }
                
                $message = "فشل إرسال جميع الرسائل ({$totalCount} رسالة). السبب: " . $mainReason;
                
                return redirect()->route('owner.collections.index')
                    ->with('error', $message);
            } else {
                // نجحت جميع الرسائل
                $message = "تم إرسال جميع الرسائل بنجاح ({$sentCount} رسالة).";
                return redirect()->route('owner.collections.index')
                    ->with('success', $message);
            }
        } else {
            // تم الجدولة فقط
            $message = 'تم جدولة الحملة بنجاح.';
            return redirect()->route('owner.collections.index')
                ->with('success', $message);
        }
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

