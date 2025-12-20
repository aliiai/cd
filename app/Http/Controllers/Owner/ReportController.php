<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Models\UserSubscription;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Report Controller for Owner
 * 
 * يعرض التقارير المختلفة للمالك
 */
class ReportController extends Controller
{
    /**
     * تقرير حالات الديون (Debt Status Report)
     * 
     * يعرض توزيع المديونين حسب الحالة مع إجمالي المبلغ
     */
    public function debtStatus(Request $request)
    {
        $ownerId = Auth::id();
        
        // حساب الإحصائيات لكل حالة
        $statuses = ['new', 'contacted', 'promise_to_pay', 'paid', 'overdue', 'failed'];
        $debtStatusReport = [];
        
        foreach ($statuses as $status) {
            $debtors = Debtor::where('owner_id', $ownerId)
                ->where('status', $status);
            
            $debtStatusReport[] = [
                'status' => $status,
                'status_text' => Debtor::getStatusText($status),
                'status_color' => Debtor::getStatusColor($status),
                'count' => $debtors->count(),
                'total_amount' => $debtors->sum('debt_amount'),
            ];
        }
        
        return view('owner.reports.debt-status', compact('debtStatusReport'));
    }

    /**
     * تقرير الرسائل (Messages Report)
     * 
     * يعرض ملخص الرسائل وجدول آخر الرسائل
     */
    public function messages(Request $request)
    {
        $ownerId = Auth::id();
        
        // جلب جميع الرسائل من خلال pivot table
        $query = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->join('clients', 'collection_campaign_clients.client_id', '=', 'clients.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->select(
                'collection_campaign_clients.*',
                'collection_campaigns.id as campaign_id',
                'collection_campaigns.channel',
                'collection_campaigns.message as message_content',
                'collection_campaigns.created_at as campaign_created_at',
                'clients.name as client_name',
                'clients.phone as client_phone',
                'clients.email as client_email'
            );

        // فلترة حسب القناة
        if ($request->filled('channel')) {
            $query->where('collection_campaigns.channel', $request->channel);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('collection_campaign_clients.status', $request->status);
        }

        // فلترة حسب الفترة الزمنية
        if ($request->filled('date_from')) {
            $query->where('collection_campaign_clients.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('collection_campaign_clients.created_at', '<=', $request->date_to);
        }

        $messages = $query->orderBy('collection_campaign_clients.created_at', 'desc')
            ->paginate(10)->appends($request->query());

        // حساب الإحصائيات
        $totalMessages = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->count();

        $smsCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'sms')
            ->count();

        $emailCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaigns.channel', 'email')
            ->count();

        $successCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'sent')
            ->count();

        $failedCount = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'failed')
            ->count();

        $successRate = $totalMessages > 0 ? ($successCount / $totalMessages) * 100 : 0;
        $failedRate = $totalMessages > 0 ? ($failedCount / $totalMessages) * 100 : 0;

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.reports.partials.messages-table', compact('messages'))->render(),
                'pagination' => $messages->links()->render(),
            ]);
        }

        return view('owner.reports.messages', compact(
            'messages',
            'totalMessages',
            'smsCount',
            'emailCount',
            'successCount',
            'failedCount',
            'successRate',
            'failedRate'
        ));
    }

    /**
     * تقرير الحملات (Campaigns Performance)
     * 
     * يعرض جدول الحملات مع الأداء
     */
    public function campaigns(Request $request)
    {
        $ownerId = Auth::id();
        
        $query = CollectionCampaign::where('owner_id', $ownerId)
            ->with(['clients']);

        // فلترة حسب القناة
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الفترة الزمنية
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $campaigns = $query->latest()->paginate(10)->appends($request->query());

        // حساب الإحصائيات (من جميع الحملات بدون فلترة)
        $allCampaignsQuery = CollectionCampaign::where('owner_id', $ownerId);
        $totalCampaigns = $allCampaignsQuery->count();
        $smsCount = $allCampaignsQuery->clone()->where('channel', 'sms')->count();
        $emailCount = $allCampaignsQuery->clone()->where('channel', 'email')->count();
        $sentCount = $allCampaignsQuery->clone()->where('status', 'sent')->count();

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.reports.partials.campaigns-table', compact('campaigns'))->render(),
                'pagination' => $campaigns->links()->render(),
            ]);
        }

        return view('owner.reports.campaigns', compact(
            'campaigns',
            'totalCampaigns',
            'smsCount',
            'emailCount',
            'sentCount'
        ));
    }

    /**
     * تقرير الاشتراك والاستهلاك (Subscription Usage)
     * 
     * يعرض معلومات الاشتراك الحالي ومؤشرات الاستهلاك
     */
    public function subscription()
    {
        $user = Auth::user();
        $ownerId = $user->id;
        
        // جلب الاشتراك النشط
        $activeSubscription = $user->getActiveSubscription();
        
        // حساب الاستهلاك
        $debtorsCount = Debtor::where('owner_id', $ownerId)->count();
        $messagesSent = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'sent')
            ->count();
        
        // الحصول على الحدود المسموحة
        $maxDebtors = $activeSubscription?->subscription->max_debtors ?? 0;
        $maxMessages = $activeSubscription?->subscription->max_messages ?? 0;
        
        // حساب النسب
        $debtorsUsage = $maxDebtors > 0 ? ($debtorsCount / $maxDebtors) * 100 : 0;
        $messagesUsage = $maxMessages > 0 ? ($messagesSent / $maxMessages) * 100 : 0;
        
        // التحقق من التنبيهات
        $warnings = [];
        if ($activeSubscription) {
            if ($activeSubscription->expires_at && $activeSubscription->expires_at->diffInDays(now()) <= 7) {
                $warnings[] = [
                    'type' => 'expiry',
                    'message' => 'الاشتراك سينتهي خلال ' . $activeSubscription->expires_at->diffInDays(now()) . ' يوم',
                    'color' => 'yellow'
                ];
            }
            if ($messagesUsage >= 90) {
                $warnings[] = [
                    'type' => 'messages',
                    'message' => 'استهلاك الرسائل قريب من الحد المسموح (' . round($messagesUsage, 1) . '%)',
                    'color' => 'orange'
                ];
            }
            if ($debtorsUsage >= 90) {
                $warnings[] = [
                    'type' => 'debtors',
                    'message' => 'عدد المديونين قريب من الحد المسموح (' . round($debtorsUsage, 1) . '%)',
                    'color' => 'orange'
                ];
            }
            if ($messagesUsage >= 100) {
                $warnings[] = [
                    'type' => 'messages',
                    'message' => 'تم تجاوز الحد المسموح للرسائل',
                    'color' => 'red'
                ];
            }
            if ($debtorsUsage >= 100) {
                $warnings[] = [
                    'type' => 'debtors',
                    'message' => 'تم تجاوز الحد المسموح لعدد المديونين',
                    'color' => 'red'
                ];
            }
        }

        return view('owner.reports.subscription', compact(
            'activeSubscription',
            'debtorsCount',
            'messagesSent',
            'maxDebtors',
            'maxMessages',
            'debtorsUsage',
            'messagesUsage',
            'warnings'
        ));
    }

    /**
     * سجل العمليات (Audit Log)
     * 
     * يعرض آخر الأنشطة للمالك
     */
    public function audit(Request $request)
    {
        $ownerId = Auth::id();
        
        // جمع الأنشطة من مصادر مختلفة
        $activities = [];
        
        // إضافة المديونين (آخر 10)
        $recentDebtors = Debtor::where('owner_id', $ownerId)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($debtor) {
                return [
                    'type' => 'add_debtor',
                    'type_text' => 'إضافة مديون',
                    'debtor_name' => $debtor->name,
                    'created_at' => $debtor->created_at,
                ];
            });
        $activities = array_merge($activities, $recentDebtors->toArray());
        
        // إضافة الحملات (آخر 10)
        $recentCampaigns = CollectionCampaign::where('owner_id', $ownerId)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($campaign) {
                return [
                    'type' => 'create_campaign',
                    'type_text' => 'إنشاء حملة',
                    'campaign_name' => $campaign->campaign_number,
                    'created_at' => $campaign->created_at,
                ];
            });
        $activities = array_merge($activities, $recentCampaigns->toArray());
        
        // إضافة تغييرات حالة الدين (من خلال collection_campaign_clients)
        $statusChanges = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->join('clients', 'collection_campaign_clients.client_id', '=', 'clients.id')
            ->where('collection_campaigns.owner_id', $ownerId)
            ->where('collection_campaign_clients.status', 'sent')
            ->select(
                'clients.name as client_name',
                'collection_campaign_clients.updated_at as created_at'
            )
            ->latest('collection_campaign_clients.updated_at')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'change_status',
                    'type_text' => 'تغيير حالة دين',
                    'client_name' => $item->client_name,
                    'created_at' => $item->created_at,
                ];
            });
        $activities = array_merge($activities, $statusChanges->toArray());
        
        // ترتيب حسب التاريخ
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Pagination يدوي
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = count($activities);
        $offset = ($page - 1) * $perPage;
        $paginatedActivities = array_slice($activities, $offset, $perPage);
        
        // إنشاء paginator يدوي
        $activities = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedActivities,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.reports.partials.audit-table', compact('activities'))->render(),
                'pagination' => $activities->links()->render(),
            ]);
        }

        return view('owner.reports.audit', compact('activities'));
    }

}
