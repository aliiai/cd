<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Analytics Controller for Owner
 * 
 * يدير صفحات التحليلات الذكية (Smart Analytics)
 */
class AnalyticsController extends Controller
{
    /**
     * عرض صفحة Collection Status Analytics
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function collectionStatus(Request $request)
    {
        $ownerId = Auth::id();
        
        // جميع المديونين للمالك الحالي
        $allDebtors = Debtor::where('owner_id', $ownerId);
        
        // 1. ملخص سريع (Summary Cards)
        $totalDebtors = $allDebtors->count();
        $totalDebtAmount = $allDebtors->sum('debt_amount');
        $paidAmount = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->sum('debt_amount');
        $collectionRate = $totalDebtAmount > 0 ? ($paidAmount / $totalDebtAmount) * 100 : 0;
        
        // 2. جدول توزيع حالات التحصيل
        $statuses = ['new', 'contacted', 'promise_to_pay', 'paid', 'overdue', 'failed'];
        $statusDistribution = [];
        
        foreach ($statuses as $status) {
            $debtorsByStatus = Debtor::where('owner_id', $ownerId)
                ->where('status', $status);
            
            $statusDistribution[] = [
                'status' => $status,
                'status_text' => Debtor::getStatusText($status),
                'status_color' => Debtor::getStatusColor($status),
                'count' => $debtorsByStatus->count(),
                'total_amount' => $debtorsByStatus->sum('debt_amount'),
            ];
        }
        
        // 3. بيانات الرسم البياني (Chart Data)
        $chartData = [
            'labels' => array_column($statusDistribution, 'status_text'),
            'counts' => array_column($statusDistribution, 'count'),
            'amounts' => array_column($statusDistribution, 'total_amount'),
            'colors' => [
                '#3B82F6', // blue (new)
                '#EAB308', // yellow (contacted)
                '#A855F7', // purple (promise_to_pay)
                '#10B981', // green (paid)
                '#EF4444', // red (overdue)
                '#6B7280', // gray (failed)
            ],
        ];
        
        // 4. تطور حالات التحصيل بمرور الوقت
        $timeFilter = $request->get('time_filter', '30'); // default: last 30 days
        $dateFrom = null;
        $dateTo = now();
        
        switch ($timeFilter) {
            case 'today':
                $dateFrom = now()->startOfDay();
                break;
            case '7':
                $dateFrom = now()->subDays(7)->startOfDay();
                break;
            case '30':
                $dateFrom = now()->subDays(30)->startOfDay();
                break;
            case 'custom':
                $dateFrom = $request->get('date_from') ? \Carbon\Carbon::parse($request->get('date_from'))->startOfDay() : now()->subDays(30)->startOfDay();
                $dateTo = $request->get('date_to') ? \Carbon\Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();
                break;
            default:
                $dateFrom = now()->subDays(30)->startOfDay();
        }
        
        // جلب تغييرات الحالة من notifications
        $statusChanges = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $ownerId)
            ->where('type', 'App\Notifications\DebtorStatusChangedNotification')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->data, true);
                return [
                    'old_status' => $data['old_status'] ?? null,
                    'new_status' => $data['new_status'] ?? null,
                    'debtor_name' => $data['debtor_name'] ?? null,
                    'created_at' => $notification->created_at,
                ];
            })
            ->filter(function ($change) {
                // فقط التحولات المهمة: Overdue → Paid و Promise to Pay → Paid
                return ($change['old_status'] === 'overdue' && $change['new_status'] === 'paid') ||
                       ($change['old_status'] === 'promise_to_pay' && $change['new_status'] === 'paid');
            });
        
        $overdueToPaid = $statusChanges->filter(function($change) {
            return $change['old_status'] === 'overdue' && $change['new_status'] === 'paid';
        })->count();
        
        $promiseToPaid = $statusChanges->filter(function($change) {
            return $change['old_status'] === 'promise_to_pay' && $change['new_status'] === 'paid';
        })->count();
        
        // 5. مؤشرات ذكية (Smart Insights)
        $insights = $this->generateInsights($statusDistribution, $totalDebtors, $collectionRate, $overdueToPaid, $promiseToPaid);
        
        return view('owner.analytics.collection-status', compact(
            'totalDebtors',
            'totalDebtAmount',
            'paidAmount',
            'collectionRate',
            'statusDistribution',
            'chartData',
            'timeFilter',
            'dateFrom',
            'dateTo',
            'overdueToPaid',
            'promiseToPaid',
            'insights'
        ));
    }
    
    /**
     * توليد المؤشرات الذكية
     * 
     * @param array $statusDistribution
     * @param int $totalDebtors
     * @param float $collectionRate
     * @param int $overdueToPaid
     * @param int $promiseToPaid
     * @return array
     */
    private function generateInsights($statusDistribution, $totalDebtors, $collectionRate, $overdueToPaid, $promiseToPaid)
    {
        $insights = [];
        
        // أكبر حالة من حيث العدد
        $maxStatus = collect($statusDistribution)->sortByDesc('count')->first();
        if ($maxStatus && $maxStatus['count'] > 0) {
            $percentage = ($maxStatus['count'] / $totalDebtors) * 100;
            $insights[] = [
                'type' => 'info',
                'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'message' => "أكبر نسبة من المديونين في حالة '{$maxStatus['status_text']}' بنسبة " . number_format($percentage, 1) . "% ({$maxStatus['count']} مديون)",
            ];
        }
        
        // حالة Overdue
        $overdueStatus = collect($statusDistribution)->firstWhere('status', 'overdue');
        if ($overdueStatus && $overdueStatus['count'] > 0) {
            $percentage = ($overdueStatus['count'] / $totalDebtors) * 100;
            if ($percentage > 20) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                    'message' => "تحذير: نسبة عالية من المديونين في حالة 'متأخر' ({$percentage}%) - يتطلب متابعة عاجلة",
                ];
            }
        }
        
        // حالة New
        $newStatus = collect($statusDistribution)->firstWhere('status', 'new');
        if ($newStatus && $newStatus['count'] > 0) {
            $percentage = ($newStatus['count'] / $totalDebtors) * 100;
            if ($percentage > 30) {
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'message' => "نسبة كبيرة من المديونين ما زالت في حالة 'جديد' ({$percentage}%) - يفضل البدء بالتواصل معهم",
                ];
            }
        }
        
        // نسبة التحصيل
        if ($collectionRate < 30) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                'message' => "نسبة التحصيل منخفضة ({$collectionRate}%) - يفضل تحسين استراتيجية التحصيل",
            ];
        } elseif ($collectionRate >= 70) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'message' => "ممتاز! نسبة التحصيل جيدة ({$collectionRate}%) - استمر في نفس الاستراتيجية",
            ];
        }
        
        // التحولات الإيجابية
        $totalPositiveChanges = $overdueToPaid + $promiseToPaid;
        if ($totalPositiveChanges > 0) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'message' => "تم تحصيل {$totalPositiveChanges} مديون خلال الفترة المحددة (من متأخر/وعد بالدفع إلى مدفوع)",
            ];
        }
        
        return $insights;
    }

    /**
     * عرض صفحة Income Analytics
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function income(Request $request)
    {
        $ownerId = Auth::id();
        
        // جلب جميع الديون المدفوعة (Paid)
        $paidDebtors = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid');
        
        // 1. ملخص الدخل (Income Summary Cards)
        $totalIncome = $paidDebtors->sum('debt_amount');
        $totalPaidDebtors = $paidDebtors->count();
        $averageDebtAmount = $totalPaidDebtors > 0 ? $totalIncome / $totalPaidDebtors : 0;
        $maxDebtAmount = $paidDebtors->max('debt_amount') ?? 0;
        
        // 2. رسم بياني للدخل عبر الزمن
        $timeFilter = $request->get('time_filter', '30'); // default: last 30 days
        $dateFrom = null;
        $dateTo = now();
        
        switch ($timeFilter) {
            case 'today':
                $dateFrom = now()->startOfDay();
                break;
            case '7':
                $dateFrom = now()->subDays(7)->startOfDay();
                break;
            case '30':
                $dateFrom = now()->subDays(30)->startOfDay();
                break;
            case 'custom':
                $dateFrom = $request->get('date_from') ? \Carbon\Carbon::parse($request->get('date_from'))->startOfDay() : now()->subDays(30)->startOfDay();
                $dateTo = $request->get('date_to') ? \Carbon\Carbon::parse($request->get('date_to'))->endOfDay() : now()->endOfDay();
                break;
            default:
                $dateFrom = now()->subDays(30)->startOfDay();
        }
        
        // جلب إشعارات التحصيل (عندما تغيرت الحالة إلى paid)
        $paidNotifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $ownerId)
            ->where('type', 'App\Notifications\DebtorStatusChangedNotification')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get()
            ->map(function ($notification) {
                $data = json_decode($notification->data, true);
                return [
                    'debtor_id' => $data['debtor_id'] ?? null,
                    'new_status' => $data['new_status'] ?? null,
                    'created_at' => $notification->created_at,
                ];
            })
            ->filter(function ($notification) {
                return $notification['new_status'] === 'paid';
            })
            ->pluck('created_at', 'debtor_id')
            ->toArray();
        
        // بيانات الرسم البياني (يومي)
        $chartData = $this->generateIncomeChartData($ownerId, $dateFrom, $dateTo, $paidNotifications);
        
        // 3. جدول تفاصيل الدخل
        $search = $request->get('search');
        $incomeQuery = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid');
        
        if ($search) {
            $incomeQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب الفترة الزمنية
        if ($timeFilter !== 'all') {
            $incomeQuery->whereBetween('updated_at', [$dateFrom, $dateTo]);
        }
        
        // فلترة حسب قيمة الدين
        $minAmount = $request->get('min_amount');
        $maxAmount = $request->get('max_amount');
        if ($minAmount) {
            $incomeQuery->where('debt_amount', '>=', $minAmount);
        }
        if ($maxAmount) {
            $incomeQuery->where('debt_amount', '<=', $maxAmount);
        }
        
        $incomeDetails = $incomeQuery->orderBy('updated_at', 'desc')->paginate(10);
        
        // إضافة تاريخ التحصيل من notifications
        $incomeDetails->getCollection()->transform(function($debtor) use ($paidNotifications) {
            $debtor->payment_date = $paidNotifications[$debtor->id] ?? $debtor->updated_at;
            return $debtor;
        });
        
        // 4. مقارنة الفترات
        $periodComparison = $this->calculatePeriodComparison($ownerId);
        
        // 5. أعلى المديونين دخلاً (Top 5) - أعلى 5 ديون مبلغاً
        $topDebtors = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->select('id', 'name', 'phone', 'email', 'debt_amount as total_paid')
            ->orderByDesc('debt_amount')
            ->limit(5)
            ->get()
            ->map(function($debtor) {
                $debtor->debts_count = 1; // كل مديون له دين واحد
                return $debtor;
            });
        
        // 6. مؤشرات ذكية
        $insights = $this->generateIncomeInsights($totalIncome, $totalPaidDebtors, $averageDebtAmount, $chartData, $periodComparison);
        
        return view('owner.analytics.income', compact(
            'totalIncome',
            'totalPaidDebtors',
            'averageDebtAmount',
            'maxDebtAmount',
            'chartData',
            'timeFilter',
            'dateFrom',
            'dateTo',
            'incomeDetails',
            'periodComparison',
            'topDebtors',
            'insights',
            'search',
            'minAmount',
            'maxAmount'
        ));
    }
    
    /**
     * توليد بيانات الرسم البياني للدخل
     * 
     * @param int $ownerId
     * @param \Carbon\Carbon $dateFrom
     * @param \Carbon\Carbon $dateTo
     * @param array $paidNotifications
     * @return array
     */
    private function generateIncomeChartData($ownerId, $dateFrom, $dateTo, $paidNotifications)
    {
        $labels = [];
        $amounts = [];
        
        // حساب الفرق بالأيام
        $daysDiff = $dateFrom->diffInDays($dateTo);
        
        if ($daysDiff <= 1) {
            // يومي - كل ساعة
            $current = $dateFrom->copy();
            while ($current <= $dateTo) {
                $labels[] = $current->format('H:i');
                $amounts[] = $this->getIncomeForPeriod($ownerId, $current->copy()->startOfHour(), $current->copy()->endOfHour(), $paidNotifications);
                $current->addHour();
            }
        } elseif ($daysDiff <= 7) {
            // أسبوعي - كل يوم
            $current = $dateFrom->copy();
            while ($current <= $dateTo) {
                $labels[] = $current->format('Y-m-d');
                $amounts[] = $this->getIncomeForPeriod($ownerId, $current->copy()->startOfDay(), $current->copy()->endOfDay(), $paidNotifications);
                $current->addDay();
            }
        } else {
            // شهري - كل يوم أو أسبوع حسب الفترة
            $current = $dateFrom->copy();
            $step = $daysDiff > 90 ? 7 : 1; // إذا أكثر من 90 يوم، كل أسبوع
            while ($current <= $dateTo) {
                $end = $current->copy()->addDays($step - 1);
                if ($end > $dateTo) $end = $dateTo;
                
                $labels[] = $current->format('Y-m-d');
                $amounts[] = $this->getIncomeForPeriod($ownerId, $current->copy()->startOfDay(), $end->copy()->endOfDay(), $paidNotifications);
                $current->addDays($step);
            }
        }
        
        return [
            'labels' => $labels,
            'amounts' => $amounts,
        ];
    }
    
    /**
     * حساب الدخل لفترة معينة
     * 
     * @param int $ownerId
     * @param \Carbon\Carbon $start
     * @param \Carbon\Carbon $end
     * @param array $paidNotifications
     * @return float
     */
    private function getIncomeForPeriod($ownerId, $start, $end, $paidNotifications)
    {
        // استخدام updated_at كتاريخ التحصيل (إذا لم يكن هناك notification)
        return Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereBetween('updated_at', [$start, $end])
            ->sum('debt_amount');
    }
    
    /**
     * حساب مقارنة الفترات
     * 
     * @param int $ownerId
     * @return array
     */
    private function calculatePeriodComparison($ownerId)
    {
        // هذا الشهر vs الشهر السابق
        $thisMonth = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('debt_amount');
        
        $lastMonth = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereMonth('updated_at', now()->subMonth()->month)
            ->whereYear('updated_at', now()->subMonth()->year)
            ->sum('debt_amount');
        
        $monthChange = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;
        
        // هذا الأسبوع vs الأسبوع السابق
        $thisWeek = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('debt_amount');
        
        $lastWeek = Debtor::where('owner_id', $ownerId)
            ->where('status', 'paid')
            ->whereBetween('updated_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->sum('debt_amount');
        
        $weekChange = $lastWeek > 0 ? (($thisWeek - $lastWeek) / $lastWeek) * 100 : 0;
        
        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'month_change' => $monthChange,
            'this_week' => $thisWeek,
            'last_week' => $lastWeek,
            'week_change' => $weekChange,
        ];
    }
    
    /**
     * توليد المؤشرات الذكية للدخل
     * 
     * @param float $totalIncome
     * @param int $totalPaidDebtors
     * @param float $averageDebtAmount
     * @param array $chartData
     * @param array $periodComparison
     * @return array
     */
    private function generateIncomeInsights($totalIncome, $totalPaidDebtors, $averageDebtAmount, $chartData, $periodComparison)
    {
        $insights = [];
        
        // أعلى يوم دخل
        if (!empty($chartData['amounts'])) {
            $maxDayIndex = array_search(max($chartData['amounts']), $chartData['amounts']);
            if ($maxDayIndex !== false && $chartData['amounts'][$maxDayIndex] > 0) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'message' => "أعلى دخل تم تحقيقه كان في " . ($chartData['labels'][$maxDayIndex] ?? 'غير محدد') . " بمبلغ " . number_format($chartData['amounts'][$maxDayIndex], 2) . " ر.س",
                ];
            }
        }
        
        // متوسط التحصيل اليومي
        if ($totalIncome > 0 && !empty($chartData['amounts'])) {
            $avgDaily = array_sum($chartData['amounts']) / count(array_filter($chartData['amounts'], fn($a) => $a > 0));
            if ($avgDaily > 0) {
                $insights[] = [
                    'type' => 'info',
                    'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'message' => "متوسط التحصيل اليومي هو " . number_format($avgDaily, 2) . " ر.س",
                ];
            }
        }
        
        // مقارنة الشهر
        if ($periodComparison['month_change'] > 0) {
            $insights[] = [
                'type' => 'success',
                'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
                'message' => "ممتاز! الدخل هذا الشهر زاد بنسبة " . number_format(abs($periodComparison['month_change']), 1) . "% مقارنة بالشهر السابق",
            ];
        } elseif ($periodComparison['month_change'] < 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
                'message' => "الدخل هذا الشهر انخفض بنسبة " . number_format(abs($periodComparison['month_change']), 1) . "% مقارنة بالشهر السابق",
            ];
        }
        
        // متوسط قيمة الدين
        if ($averageDebtAmount > 0) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                'message' => "متوسط قيمة الدين المحصل هو " . number_format($averageDebtAmount, 2) . " ر.س",
            ];
        }
        
        return $insights;
    }
}
