<?php

namespace App\Console\Commands;

use App\Models\Debtor;
use App\Models\DebtInstallment;
use App\Models\CollectionCampaign;
use App\Services\FourJawalyService;
use App\Services\PaymobService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Command لإرسال تذكيرات تلقائية في تاريخ استحقاق الدفع
 * 
 * يتم تشغيل هذا الأمر يومياً للتحقق من الديون والدفعات المستحقة
 * وإرسال رسائل SMS تلقائياً للمديونين
 */
class SendDueDateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-due-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إرسال تذكيرات تلقائية للمديونين في تاريخ استحقاق الدفع';

    /**
     * Execute the console command.
     */
    public function handle(FourJawalyService $smsService, PaymobService $paymobService)
    {
        $this->info('بدء عملية إرسال التذكيرات التلقائية...');
        
        $today = Carbon::today();
        $totalSent = 0;
        $totalFailed = 0;
        
        // 1. جلب الديون العادية المستحقة اليوم (غير مقسمة على دفعات)
        $regularDebts = Debtor::where('clients.has_installments', false)
            ->where('clients.due_date', $today)
            ->where('clients.status', '!=', 'paid')
            ->whereDoesntHave('campaigns', function($query) use ($today) {
                $query->where('collection_campaigns.status', 'sent')
                    ->whereDate('collection_campaigns.created_at', $today)
                    ->where('collection_campaigns.send_type', 'auto');
            })
            ->with(['owner', 'installments'])
            ->get();
        
        $this->info("تم العثور على {$regularDebts->count()} دين عادي مستحق اليوم");
        
        // 2. جلب الدفعات المستحقة اليوم
        $dueInstallments = DebtInstallment::where('due_date', $today)
            ->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled')
            ->with(['debtor' => function($query) {
                $query->with('owner');
            }])
            ->get();
        
        $this->info("تم العثور على {$dueInstallments->count()} دفعة مستحقة اليوم");
        
        // 3. معالجة الديون العادية
        foreach ($regularDebts as $debtor) {
            // التحقق من وجود اشتراك نشط
            $activeSubscription = $debtor->owner->getActiveSubscription();
            if (!$activeSubscription) {
                $this->warn("المالك {$debtor->owner->name} (ID: {$debtor->owner_id}) لا يملك اشتراك نشط. تخطي الدين ID: {$debtor->id}");
                continue;
            }
            
            // التحقق من حدود الرسائل
            $subscription = $activeSubscription->subscription;
            $maxMessages = $subscription->max_messages ?? 0;
            
            if ($maxMessages > 0) {
                $messagesSent = DB::table('collection_campaign_clients')
                    ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                    ->where('collection_campaigns.owner_id', $debtor->owner_id)
                    ->where('collection_campaign_clients.status', 'sent')
                    ->count();
                
                if ($messagesSent >= $maxMessages) {
                    $this->warn("المالك {$debtor->owner->name} (ID: {$debtor->owner_id}) استنفد حد الرسائل. تخطي الدين ID: {$debtor->id}");
                    continue;
                }
            }
            
            // إنشاء رابط دفع تلقائياً إذا لم يكن موجوداً
            $this->ensurePaymentLink($debtor, $paymobService);
            
            // إنشاء حملة تلقائية
            $campaign = $this->createAutoCampaign($debtor, 'regular');
            
            // إرسال الرسالة
            $result = $this->sendReminderMessage($smsService, $debtor, $campaign, null);
            
            if ($result['success']) {
                $totalSent++;
                $this->info("✓ تم إرسال تذكير للدين ID: {$debtor->id} - {$debtor->name}");
            } else {
                $totalFailed++;
                $this->error("✗ فشل إرسال تذكير للدين ID: {$debtor->id} - {$debtor->name}: {$result['message']}");
            }
        }
        
        // 4. معالجة الدفعات
        foreach ($dueInstallments as $installment) {
            $debtor = $installment->debtor;
            
            if (!$debtor) {
                $this->warn("الدفعة ID: {$installment->id} لا تحتوي على مديون. تخطي...");
                continue;
            }
            
            // التحقق من وجود اشتراك نشط
            $activeSubscription = $debtor->owner->getActiveSubscription();
            if (!$activeSubscription) {
                $this->warn("المالك {$debtor->owner->name} (ID: {$debtor->owner_id}) لا يملك اشتراك نشط. تخطي الدفعة ID: {$installment->id}");
                continue;
            }
            
            // التحقق من حدود الرسائل
            $subscription = $activeSubscription->subscription;
            $maxMessages = $subscription->max_messages ?? 0;
            
            if ($maxMessages > 0) {
                $messagesSent = DB::table('collection_campaign_clients')
                    ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                    ->where('collection_campaigns.owner_id', $debtor->owner_id)
                    ->where('collection_campaign_clients.status', 'sent')
                    ->count();
                
                if ($messagesSent >= $maxMessages) {
                    $this->warn("المالك {$debtor->owner->name} (ID: {$debtor->owner_id}) استنفد حد الرسائل. تخطي الدفعة ID: {$installment->id}");
                    continue;
                }
            }
            
            // التحقق من عدم إرسال رسالة تلقائية لهذه الدفعة اليوم
            $alreadySent = DB::table('collection_campaign_clients')
                ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                ->where('collection_campaigns.owner_id', $debtor->owner_id)
                ->where('collection_campaign_clients.client_id', $debtor->id)
                ->where('collection_campaigns.send_type', 'auto')
                ->whereDate('collection_campaigns.created_at', $today)
                ->where('collection_campaign_clients.status', 'sent')
                ->exists();
            
            if ($alreadySent) {
                $this->info("تم إرسال رسالة تلقائية لهذا المديون اليوم. تخطي الدفعة ID: {$installment->id}");
                continue;
            }
            
            // إنشاء رابط دفع تلقائياً إذا لم يكن موجوداً
            $this->ensurePaymentLink($debtor, $paymobService);
            
            // إنشاء حملة تلقائية
            $campaign = $this->createAutoCampaign($debtor, 'installment');
            
            // إرسال الرسالة
            $result = $this->sendReminderMessage($smsService, $debtor, $campaign, $installment);
            
            if ($result['success']) {
                $totalSent++;
                $this->info("✓ تم إرسال تذكير للدفعة ID: {$installment->id} - المديون: {$debtor->name}");
            } else {
                $totalFailed++;
                $this->error("✗ فشل إرسال تذكير للدفعة ID: {$installment->id} - المديون: {$debtor->name}: {$result['message']}");
            }
        }
        
        $this->info("تم الانتهاء من عملية الإرسال التلقائي.");
        $this->info("إجمالي المرسل: {$totalSent}");
        $this->info("إجمالي الفاشل: {$totalFailed}");
        
        return 0;
    }
    
    /**
     * إنشاء حملة تلقائية
     * 
     * @param Debtor $debtor
     * @param string $type 'regular' أو 'installment'
     * @return CollectionCampaign
     */
    private function createAutoCampaign(Debtor $debtor, string $type): CollectionCampaign
    {
        $campaign = CollectionCampaign::create([
            'owner_id' => $debtor->owner_id,
            'campaign_number' => 'AUTO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6)),
            'channel' => 'sms',
            'template' => 'auto_due_date_reminder',
            'message' => $this->getDefaultMessage($debtor, $type),
            'send_type' => 'auto', // نوع جديد للإرسال التلقائي
            'scheduled_at' => null,
            'status' => 'pending',
            'total_recipients' => 1,
            'sent_count' => 0,
            'failed_count' => 0,
        ]);
        
        // ربط المديون بالحملة
        $campaign->debtors()->attach($debtor->id, [
            'status' => 'pending',
        ]);
        
        return $campaign;
    }
    
    /**
     * الحصول على الرسالة الافتراضية
     * 
     * @param Debtor $debtor
     * @param string $type
     * @return string
     */
    private function getDefaultMessage(Debtor $debtor, string $type): string
    {
        if ($type === 'installment') {
            $amount = $debtor->remaining_amount > 0 
                ? number_format($debtor->remaining_amount, 2)
                : number_format($debtor->debt_amount, 2);
            
            return "مرحباً {{name}}، نود تذكيرك بأن لديك دفعة مستحقة اليوم بقيمة {{amount}} ر.س. يرجى تسوية المبلغ في أقرب وقت ممكن. شكراً لتعاونك.";
        }
        
        $amount = $debtor->remaining_amount > 0 
            ? number_format($debtor->remaining_amount, 2)
            : number_format($debtor->debt_amount, 2);
        
        return "مرحباً {{name}}، نود تذكيرك بأن لديك مبلغ مستحق للدفع اليوم بقيمة {{amount}} ر.س. يرجى تسوية المبلغ في أقرب وقت ممكن. شكراً لتعاونك.";
    }
    
    /**
     * إرسال رسالة التذكير
     * 
     * @param FourJawalyService $smsService
     * @param Debtor $debtor
     * @param CollectionCampaign $campaign
     * @param DebtInstallment|null $installment
     * @return array
     */
    private function sendReminderMessage(
        FourJawalyService $smsService,
        Debtor $debtor,
        CollectionCampaign $campaign,
        ?DebtInstallment $installment
    ): array {
        try {
            // استبدال placeholders في الرسالة
            $message = $campaign->message;
            
            // استبدال {{name}} باسم المديون
            $message = str_replace(['{{name}}', '@{{name}}'], $debtor->name ?? 'العميل', $message);
            
            // استبدال {{amount}} بمبلغ الدين
            if ($installment) {
                $debtAmount = number_format($installment->amount, 2);
            } else {
                $debtAmount = $debtor->has_installments 
                    ? number_format($debtor->remaining_amount, 2)
                    : number_format($debtor->debt_amount, 2);
            }
            $message = str_replace(['{{amount}}', '@{{amount}}'], $debtAmount, $message);
            
            // استبدال {{link}} برابط الدفع
            // استخدام رابط مختصر لتجنب تجاوز حد الرسائل (15 رسالة SMS)
            $paymentLinkForSms = 'غير متوفر';
            if ($debtor->payment_link) {
                try {
                    // إنشاء رابط مختصر باستخدام route خاص
                    $paymentLinkForSms = route('owner.payment.short-link', ['debtor' => $debtor->id]);
                } catch (\Exception $e) {
                    // إذا فشل إنشاء route، استخدم الرابط الكامل (لكن سيتم رفضه من 4jawaly إذا كان طويلاً)
                    Log::warning('Failed to generate short payment link', [
                        'debtor_id' => $debtor->id,
                        'error' => $e->getMessage()
                    ]);
                    $paymentLinkForSms = $debtor->payment_link;
                }
            }
            $message = str_replace(['{{link}}', '@{{link}}'], $paymentLinkForSms, $message);
            
            // إرسال SMS
            $result = $smsService->sendSMS($debtor->phone, $message, [
                'event_type' => 'auto_due_date_reminder',
                'entity_type' => CollectionCampaign::class,
                'entity_id' => $campaign->id,
            ]);
            
            // تحديث حالة الحملة والمديون
            if ($result['success']) {
                $campaign->update([
                    'status' => 'sent',
                    'sent_count' => 1,
                ]);
                
                $campaign->debtors()->updateExistingPivot($debtor->id, [
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
                
                return ['success' => true, 'message' => 'تم الإرسال بنجاح'];
            } else {
                $errorMessage = $result['error'] ?? 
                              (isset($result['message']) && stripos($result['message'], 'فشل') !== false ? $result['message'] : null) ??
                              ($result['message'] ?? 'فشل الإرسال');
                
                $campaign->update([
                    'status' => 'failed',
                    'failed_count' => 1,
                ]);
                
                $campaign->debtors()->updateExistingPivot($debtor->id, [
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                ]);
                
                return ['success' => false, 'message' => $errorMessage];
            }
        } catch (\Exception $e) {
            Log::error('خطأ في إرسال تذكير تلقائي', [
                'debtor_id' => $debtor->id,
                'campaign_id' => $campaign->id,
                'error' => $e->getMessage(),
            ]);
            
            $campaign->update([
                'status' => 'failed',
                'failed_count' => 1,
            ]);
            
            $campaign->debtors()->updateExistingPivot($debtor->id, [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * التأكد من وجود رابط دفع للمديون
     * 
     * @param Debtor $debtor
     * @param PaymobService $paymobService
     * @return void
     */
    private function ensurePaymentLink(Debtor $debtor, PaymobService $paymobService): void
    {
        if ($debtor->payment_link) {
            return; // رابط الدفع موجود بالفعل
        }

        try {
            $amount = $debtor->has_installments 
                ? $debtor->remaining_amount 
                : $debtor->debt_amount;
            
            if ($amount <= 0) {
                return; // لا يوجد مبلغ مستحق
            }

            $result = $paymobService->generatePaymentLink(
                amount: $amount,
                debtorName: $debtor->name,
                debtorEmail: $debtor->email ?? $debtor->owner->email,
                debtorPhone: $debtor->phone,
                debtorId: $debtor->id
            );

            if ($result && isset($result['payment_link'])) {
                $debtor->update(['payment_link' => $result['payment_link']]);
                $this->info("✓ تم إنشاء رابط دفع للمديون ID: {$debtor->id}");
            }
        } catch (\Exception $e) {
            Log::warning('Failed to generate payment link for debtor in auto reminder', [
                'debtor_id' => $debtor->id,
                'error' => $e->getMessage(),
            ]);
            // لا نوقف العملية إذا فشل إنشاء رابط الدفع
        }
    }
}

