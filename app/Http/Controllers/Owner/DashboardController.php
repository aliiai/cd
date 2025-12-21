<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the owner dashboard.
     */
    public function index()
    {
        $ownerId = Auth::id();
        $user = Auth::user();
        
        // ========== 1. البطاقات الإحصائية العلوية ==========
        
        // إجمالي المديونين
        $totalDebtors = Debtor::where('owner_id', $ownerId)->count();
        $totalDebtorsLastMonth = Debtor::where('owner_id', $ownerId)
            ->where('created_at', '<=', now()->subMonth()->endOfMonth())
            ->count();
        $debtorsChange = $totalDebtorsLastMonth > 0 
            ? (($totalDebtors - $totalDebtorsLastMonth) / $totalDebtorsLastMonth) * 100 
            : ($totalDebtors > 0 ? 100 : 0);
        
        // إجمالي قيمة الديون
        $totalDebtAmount = Debtor::where('owner_id', $ownerId)->sum('debt_amount');
        $totalDebtAmountLastMonth = Debtor::where('owner_id', $ownerId)
            ->where('created_at', '<=', now()->subMonth()->endOfMonth())
            ->sum('debt_amount');
        $debtAmountChange = $totalDebtAmountLastMonth > 0 
            ? (($totalDebtAmount - $totalDebtAmountLastMonth) / $totalDebtAmountLastMonth) * 100 
            : ($totalDebtAmount > 0 ? 100 : 0);
        
        // إجمالي المبالغ المحصلة
        $totalCollected = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->sum('debt_amount');
        $totalCollectedLastMonth = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->where('updated_at', '<=', now()->subMonth()->endOfMonth())
            ->sum('debt_amount');
        $collectedChange = $totalCollectedLastMonth > 0 
            ? (($totalCollected - $totalCollectedLastMonth) / $totalCollectedLastMonth) * 100 
            : ($totalCollected > 0 ? 100 : 0);
        
        // إجمالي الرسائل المرسلة
        $totalMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $smsCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'sms')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $emailCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'email')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        // إحصائيات الإرسال التلقائي
        $autoMessagesCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.send_type', 'auto')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $autoMessagesToday = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.send_type', 'auto')
            ->where('collection_campaign_clients.status', 'sent')
            ->whereDate('collection_campaign_clients.sent_at', today())
            ->count();
        
        $autoMessagesPercentage = $totalMessages > 0 ? ($autoMessagesCount / $totalMessages) * 100 : 0;
        
        // استخدام الذكاء الاصطناعي (عدد الحملات التي استخدمت AI)
        // يمكن اعتبار أن الحملات التي تحتوي على template أو message مولد بواسطة AI
        $aiUsageCount = CollectionCampaign::where('owner_id', $ownerId)
            ->whereNotNull('template')
            ->count();
        
        $activeSubscription = $user->getActiveSubscription();
        $maxMessages = $activeSubscription?->subscription->max_messages ?? 0;
        $aiUsagePercentage = $maxMessages > 0 ? ($aiUsageCount / $maxMessages) * 100 : 0;
        
        // ========== 2. بيانات الرسوم البيانية ==========
        
        // تطور التحصيل عبر الزمن (أسبوعي - آخر 7 أيام)
        $collectionTrendData = $this->getCollectionTrendDataWeekly($ownerId);
        
        // توزيع حالات الديون
        $statusDistributionData = $this->getStatusDistributionData($ownerId);
        
        // استخدام قنوات التواصل
        $channelUsageData = $this->getChannelUsageData($ownerId);
        
        // ========== 3. التنبيهات الذكية ==========
        
        $alerts = $this->generateSmartAlerts($ownerId, $user, $activeSubscription, $totalDebtors, $totalMessages, $maxMessages);
        
        // ========== 4. نشاط اليوم ==========
        
        $todayMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->whereDate('collection_campaign_clients.created_at', today())
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $todayDebtors = Debtor::where('owner_id', $ownerId)
            ->whereDate('created_at', today())
            ->count();
        
        $todayCollected = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereDate('updated_at', today())
            ->sum('debt_amount');
        
        return view('owner.dashboard', compact(
            'totalDebtors',
            'debtorsChange',
            'totalDebtAmount',
            'debtAmountChange',
            'totalCollected',
            'collectedChange',
            'totalMessages',
            'smsCount',
            'emailCount',
            'aiUsageCount',
            'aiUsagePercentage',
            'maxMessages',
            'autoMessagesCount',
            'autoMessagesToday',
            'autoMessagesPercentage',
            'collectionTrendData',
            'statusDistributionData',
            'channelUsageData',
            'alerts',
            'activeSubscription',
            'todayMessages',
            'todayDebtors',
            'todayCollected'
        ));
    }
    
    /**
     * جلب بيانات تطور التحصيل عبر الزمن
     */
    private function getCollectionTrendData($ownerId, $days = 30)
    {
        $dateFrom = now()->subDays($days)->startOfDay();
        $dateTo = now()->endOfDay();
        
        $labels = [];
        $amounts = [];
        
        $current = $dateFrom->copy();
        while ($current <= $dateTo) {
            $labels[] = $current->format('Y-m-d');
            
            // حساب المبالغ المحصلة في هذا اليوم
            $dailyAmount = Debtor::where('owner_id', $ownerId)
                ->where('status', 'paid')
                ->whereDate('updated_at', $current->format('Y-m-d'))
                ->sum('debt_amount');
            
            $amounts[] = $dailyAmount;
            $current->addDay();
        }
        
        return [
            'labels' => $labels,
            'amounts' => $amounts,
        ];
    }
    
    /**
     * جلب بيانات تطور التحصيل عبر الزمن (أسبوعي - آخر 7 أيام)
     */
    private function getCollectionTrendDataWeekly($ownerId)
    {
        $labels = [];
        $amounts = [];
        $daysOfWeek = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayIndex = $date->dayOfWeek;
            $dayName = $daysOfWeek[$dayIndex];
            $labels[] = $dayName;
            
            $amount = Debtor::where('owner_id', $ownerId)
                ->where('status', 'paid')
                ->whereDate('updated_at', $date->format('Y-m-d'))
                ->sum('debt_amount');
            
            $amounts[] = $amount;
        }
        
        return [
            'labels' => $labels,
            'amounts' => $amounts,
        ];
    }
    
    /**
     * جلب بيانات توزيع حالات الديون
     */
    private function getStatusDistributionData($ownerId)
    {
        $statuses = ['new', 'contacted', 'promise_to_pay', 'paid', 'overdue', 'failed'];
        $labels = [];
        $counts = [];
        $colors = [
            'rgb(92, 112, 224)', // primary (new)
            'rgb(129, 95, 228)', // secondary (contacted)
            'rgb(92, 112, 224)', // primary (promise_to_pay)
            'rgb(129, 95, 228)', // secondary (paid)
            '#EF4444', // red (overdue)
            '#6B7280', // gray (failed)
        ];
        
        foreach ($statuses as $index => $status) {
            $count = Debtor::where('owner_id', $ownerId)
                ->where('status', $status)
                ->count();
            
            $labels[] = Debtor::getStatusText($status);
            $counts[] = $count;
        }
        
        return [
            'labels' => $labels,
            'counts' => $counts,
            'colors' => $colors,
        ];
    }
    
    /**
     * جلب بيانات استخدام قنوات التواصل
     */
    private function getChannelUsageData($ownerId)
    {
        $smsCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'sms')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $emailCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'email')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        return [
            'labels' => ['SMS', 'Email'],
            'counts' => [$smsCount, $emailCount],
            'colors' => ['rgb(92, 112, 224)', 'rgb(129, 95, 228)'],
        ];
    }
    
    /**
     * توليد التنبيهات الذكية
     */
    private function generateSmartAlerts($ownerId, $user, $activeSubscription, $totalDebtors, $totalMessages, $maxMessages)
    {
        $alerts = [];
        
        // تنبيه انتهاء الاشتراك
        if ($activeSubscription && $activeSubscription->expires_at) {
            $daysUntilExpiry = now()->diffInDays($activeSubscription->expires_at, false);
            if ($daysUntilExpiry <= 7 && $daysUntilExpiry > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'title' => 'اشتراكك قارب على الانتهاء',
                    'message' => "اشتراكك سينتهي خلال {$daysUntilExpiry} يوم. يرجى تجديد الاشتراك لتجنب تعطيل الخدمة.",
                ];
            } elseif ($daysUntilExpiry <= 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'title' => 'اشتراكك منتهي',
                    'message' => 'اشتراكك منتهي. يرجى تجديد الاشتراك لاستئناف الخدمة.',
                ];
            }
        } elseif (!$activeSubscription) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'title' => 'لا يوجد اشتراك نشط',
                'message' => 'ليس لديك اشتراك نشط حالياً. يرجى الاشتراك لاستخدام الخدمة.',
            ];
        }
        
        // تنبيه الاقتراب من حد الرسائل
        if ($maxMessages > 0) {
            $messagesUsage = ($totalMessages / $maxMessages) * 100;
            if ($messagesUsage >= 90) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'title' => 'اقتربت من حد الرسائل',
                    'message' => "استخدمت " . number_format($messagesUsage, 1) . "% من حد الرسائل ({$totalMessages}/{$maxMessages}).",
                ];
            } elseif ($messagesUsage >= 100) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'title' => 'وصلت إلى حد الرسائل',
                    'message' => "وصلت إلى الحد الأقصى للرسائل ({$maxMessages}). يرجى ترقية الاشتراك لإرسال المزيد.",
                ];
            }
        }
        
        // تنبيه استخدام AI مرتفع (static)
        $aiUsageCount = CollectionCampaign::where('owner_id', $ownerId)
            ->whereNotNull('template')
            ->count();
        if ($aiUsageCount > 100) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'title' => 'استخدام مرتفع للذكاء الاصطناعي',
                'message' => "استخدمت الذكاء الاصطناعي {$aiUsageCount} مرة. هذا مؤشر جيد على كفاءة استخدام المنصة.",
            ];
        }
        
        // تنبيه فشل متكرر في الإرسال
        $failedMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'failed')
            ->where('collection_campaign_clients.created_at', '>=', now()->subDays(7))
            ->count();
        
        if ($failedMessages > 10) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'title' => 'فشل متكرر في الإرسال',
                'message' => "فشل إرسال {$failedMessages} رسالة خلال آخر 7 أيام. يرجى التحقق من بيانات المديونين.",
            ];
        }
        
        // تنبيه ديون متأخرة عالية
        $overdueCount = Debtor::where('owner_id', $ownerId)
            ->where('status', 'overdue')
            ->count();
        $overduePercentage = $totalDebtors > 0 ? ($overdueCount / $totalDebtors) * 100 : 0;
        
        if ($overduePercentage > 30) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'title' => 'نسبة عالية من الديون المتأخرة',
                'message' => "لديك {$overdueCount} مديون متأخر (" . number_format($overduePercentage, 1) . "%). يفضل متابعة عاجلة.",
            ];
        }
        
        return $alerts;
    }
    
    /**
     * جلب بيانات التحصيل حسب الفلتر (AJAX)
     */
    public function getCollectionData(Request $request)
    {
        $ownerId = Auth::id();
        $filter = $request->input('filter', 'weekly'); // daily, weekly, monthly, yearly
        
        $labels = [];
        $amounts = [];
        
        // أسماء الأيام بالعربي
        $daysOfWeek = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        
        // أسماء الشهور بالعربي
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        
        switch ($filter) {
            case 'daily':
                // ساعات اليوم (24 ساعة)
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = $i . ':00';
                    $amount = Debtor::where('owner_id', $ownerId)
                        ->where('status', 'paid')
                        ->whereDate('updated_at', today())
                        ->whereRaw('HOUR(updated_at) = ?', [$i])
                        ->sum('debt_amount');
                    $amounts[] = $amount;
                }
                break;
                
            case 'weekly':
                // آخر 7 أيام
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $dayIndex = $date->dayOfWeek;
                    $dayName = $daysOfWeek[$dayIndex];
                    $labels[] = $dayName;
                    
                    $amount = Debtor::where('owner_id', $ownerId)
                        ->where('status', 'paid')
                        ->whereDate('updated_at', $date->format('Y-m-d'))
                        ->sum('debt_amount');
                    $amounts[] = $amount;
                }
                break;
                
            case 'monthly':
                // أسابيع الشهر الحالي (4-5 أسابيع)
                $startOfMonth = now()->startOfMonth();
                $endOfMonth = now()->endOfMonth();
                $currentDate = $startOfMonth->copy();
                $weekNumber = 1;
                
                while ($currentDate->lte($endOfMonth)) {
                    $weekStart = $currentDate->copy();
                    $weekEnd = $currentDate->copy()->addDays(6);
                    
                    // التأكد من عدم تجاوز نهاية الشهر
                    if ($weekEnd->gt($endOfMonth)) {
                        $weekEnd = $endOfMonth->copy();
                    }
                    
                    $labels[] = 'أسبوع ' . $weekNumber;
                    
                    $amount = Debtor::where('owner_id', $ownerId)
                        ->where('status', 'paid')
                        ->whereBetween('updated_at', [
                            $weekStart->format('Y-m-d 00:00:00'),
                            $weekEnd->format('Y-m-d 23:59:59')
                        ])
                        ->sum('debt_amount');
                    $amounts[] = $amount;
                    
                    $currentDate->addDays(7);
                    $weekNumber++;
                    
                    // منع الحلقة اللانهائية
                    if ($weekNumber > 6) break;
                }
                break;
                
            case 'yearly':
                // آخر 12 شهر (سنوي - نفس شهري لكن بتجميع سنوي)
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $labels[] = $months[$date->month - 1];
                    
                    $amount = Debtor::where('owner_id', $ownerId)
                        ->where('status', 'paid')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->sum('debt_amount');
                    $amounts[] = $amount;
                }
                break;
        }
        
        return response()->json([
            'labels' => $labels,
            'amounts' => $amounts,
        ]);
    }
}

