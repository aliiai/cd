<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Models\UserSubscription;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // ========== 1. الإحصائيات الرئيسية ==========
        
        // عدد مقدمي الخدمة (Owners)
        $totalOwners = User::whereHas('roles', function($query) {
            $query->where('name', 'owner');
        })->count();
        
        $activeOwners = User::whereHas('roles', function($query) {
            $query->where('name', 'owner');
        })->where('is_active', true)->count();
        
        $inactiveOwners = $totalOwners - $activeOwners;
        
        // إجمالي المديونين عبر جميع المالكين
        $totalDebtors = Debtor::count();
        $totalDebtAmount = Debtor::sum('debt_amount');
        $totalCollected = Debtor::where('status', 'paid')->sum('debt_amount');
        $collectionRate = $totalDebtAmount > 0 ? ($totalCollected / $totalDebtAmount) * 100 : 0;
        
        // إجمالي الرسائل المرسلة
        $totalMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $smsCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.channel', 'sms')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        $emailCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.channel', 'email')
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        // الاشتراكات النشطة
        $activeSubscriptions = UserSubscription::where('status', 'active')
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->count();
        
        $totalSubscriptions = UserSubscription::count();
        
        // استخدام الذكاء الاصطناعي
        $aiUsageCount = CollectionCampaign::whereNotNull('template')->count();
        $totalCampaigns = CollectionCampaign::count();
        $aiUsagePercentage = $totalCampaigns > 0 ? ($aiUsageCount / $totalCampaigns) * 100 : 0;
        
        // ========== 2. بيانات الرسوم البيانية ==========
        
        // توزيع المديونين حسب الحالة
        $statusDistribution = $this->getStatusDistribution();
        
        // نموذج الاشتراكات (أكثر الباقات استخداماً)
        $subscriptionUsage = $this->getSubscriptionUsage();
        
        // نشاط الرسائل خلال آخر 7 أيام (أيام الأسبوع بالعربي)
        $messagesActivity = $this->getMessagesActivityWeekly();
        
        // ========== 3. أحدث الأنشطة ==========
        
        $recentOwners = User::whereHas('roles', function($query) {
            $query->where('name', 'owner');
        })
        ->latest()
        ->limit(5)
        ->get();
        
        $recentCampaigns = CollectionCampaign::with('owner')
            ->latest()
            ->limit(5)
            ->get();
        
        // ========== 4. إحصائيات إضافية ==========
        
        $todayMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaign_clients.status', 'sent')
            ->whereDate('collection_campaign_clients.created_at', today())
            ->count();
        
        $todayDebtors = Debtor::whereDate('created_at', today())->count();
        
        // المستخدمين المنضمين اليوم (Owners فقط)
        $todayUsers = User::whereHas('roles', function($query) {
            $query->where('name', 'owner');
        })->whereDate('created_at', today())->count();
        
        return view('admin.dashboard', compact(
            'totalOwners',
            'activeOwners',
            'inactiveOwners',
            'totalDebtors',
            'totalDebtAmount',
            'totalCollected',
            'collectionRate',
            'totalMessages',
            'smsCount',
            'emailCount',
            'activeSubscriptions',
            'totalSubscriptions',
            'aiUsageCount',
            'aiUsagePercentage',
            'statusDistribution',
            'subscriptionUsage',
            'messagesActivity',
            'recentOwners',
            'recentCampaigns',
            'todayMessages',
            'todayDebtors',
            'todayUsers'
        ));
    }
    
    /**
     * جلب توزيع حالات الديون
     */
    private function getStatusDistribution()
    {
        $statuses = ['new', 'contacted', 'promise_to_pay', 'paid', 'overdue', 'failed'];
        $distribution = [];
        
        foreach ($statuses as $status) {
            $count = Debtor::where('status', $status)->count();
            $distribution[] = [
                'status' => $status,
                'status_text' => Debtor::getStatusText($status),
                'count' => $count,
            ];
        }
        
        return $distribution;
    }
    
    /**
     * جلب استخدام الباقات
     */
    private function getSubscriptionUsage()
    {
        return Subscription::withCount('userSubscriptions')
            ->orderBy('user_subscriptions_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function($subscription) {
                return [
                    'name' => $subscription->name,
                    'count' => $subscription->user_subscriptions_count,
                    'price' => $subscription->price,
                ];
            });
    }
    
    /**
     * جلب نشاط الرسائل خلال آخر 7 أيام (أيام الأسبوع بالعربي)
     */
    private function getMessagesActivityWeekly()
    {
        $labels = [];
        $counts = [];
        $daysOfWeek = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayIndex = $date->dayOfWeek;
            $labels[] = $daysOfWeek[$dayIndex];
            
            $count = DB::table('collection_campaign_clients')
                ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                ->where('collection_campaign_clients.status', 'sent')
                ->whereDate('collection_campaign_clients.created_at', $date->format('Y-m-d'))
                ->count();
            
            $counts[] = $count;
        }
        
        return [
            'labels' => $labels,
            'counts' => $counts,
        ];
    }
    
    /**
     * جلب بيانات الرسائل حسب الفلتر (AJAX)
     */
    public function getMessagesData(Request $request)
    {
        $filter = $request->input('filter', 'weekly'); // daily, weekly, monthly, yearly
        
        $labels = [];
        $counts = [];
        
        // أسماء الأيام بالعربي
        $daysOfWeek = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        
        // أسماء الشهور بالعربي
        $months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'];
        
        switch ($filter) {
            case 'daily':
                // ساعات اليوم (24 ساعة)
                for ($i = 0; $i < 24; $i++) {
                    $labels[] = $i . ':00';
                    $count = DB::table('collection_campaign_clients')
                        ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                        ->where('collection_campaign_clients.status', 'sent')
                        ->whereDate('collection_campaign_clients.created_at', today())
                        ->whereRaw('HOUR(collection_campaign_clients.created_at) = ?', [$i])
                        ->count();
                    $counts[] = $count;
                }
                break;
                
            case 'weekly':
                // أيام الأسبوع (آخر 7 أيام)
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    // Carbon uses 0=Sunday, 1=Monday, etc. which matches our array
                    $dayIndex = $date->dayOfWeek;
                    $dayName = $daysOfWeek[$dayIndex];
                    $labels[] = $dayName;
                    
                    $count = DB::table('collection_campaign_clients')
                        ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                        ->where('collection_campaign_clients.status', 'sent')
                        ->whereDate('collection_campaign_clients.created_at', $date->format('Y-m-d'))
                        ->count();
                    $counts[] = $count;
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
                    
                    $count = DB::table('collection_campaign_clients')
                        ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                        ->where('collection_campaign_clients.status', 'sent')
                        ->whereBetween('collection_campaign_clients.created_at', [
                            $weekStart->format('Y-m-d 00:00:00'),
                            $weekEnd->format('Y-m-d 23:59:59')
                        ])
                        ->count();
                    $counts[] = $count;
                    
                    $currentDate->addDays(7);
                    $weekNumber++;
                    
                    // منع الحلقة اللانهائية
                    if ($weekNumber > 6) break;
                }
                break;
                
            case 'yearly':
                // شهور السنة (12 شهر)
                for ($i = 11; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $labels[] = $months[$date->month - 1];
                    
                    $count = DB::table('collection_campaign_clients')
                        ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
                        ->where('collection_campaign_clients.status', 'sent')
                        ->whereYear('collection_campaign_clients.created_at', $date->year)
                        ->whereMonth('collection_campaign_clients.created_at', $date->month)
                        ->count();
                    $counts[] = $count;
                }
                break;
        }
        
        return response()->json([
            'labels' => $labels,
            'counts' => $counts,
        ]);
    }
}

