<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Models\Subscription;
use App\Models\UserSubscription;
use App\Models\SubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * AI Report Controller for Admin
 * 
 * يعرض تقارير AI المختلفة للأدمن
 */
class AiReportController extends Controller
{
    /**
     * Display the main AI reports page.
     */
    public function index()
    {
        return view('admin.ai-reports.index');
    }

    /**
     * تقارير مقدمي الخدمة (Service Providers Reports)
     * 
     * يعرض جدول تحليلي لمقدمي الخدمة مع:
     * - الحالة (Active / Suspended)
     * - الباقة الحالية
     * - عدد المديونين
     * - عدد الرسائل المرسلة
     * - نسبة التحصيل
     * - AI Usage (static حالياً)
     */
    public function serviceProviders(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->with(['activeSubscription.subscription', 'subscriptions.subscription']);

        // البحث بالاسم أو البريد الإلكتروني
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // الترتيب
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        if ($sortBy === 'ai_usage') {
            // ترتيب حسب استهلاك AI (static حالياً)
            $query->orderBy('name', $sortOrder);
        } elseif ($sortBy === 'collection_rate') {
            // ترتيب حسب نسبة التحصيل
            $query->orderBy('name', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $providers = $query->paginate(10)->appends($request->query());

        // حساب الإحصائيات لكل مقدم خدمة
        foreach ($providers as $provider) {
            // عدد المديونين
            $provider->debtors_count = Debtor::where('owner_id', $provider->id)->count();
            
            // عدد الرسائل المرسلة (من الحملات)
            $provider->messages_sent = CollectionCampaign::where('owner_id', $provider->id)
                ->where('status', 'sent')
                ->sum('sent_count');
            
            // نسبة التحصيل (حساب بسيط)
            $totalDebt = Debtor::where('owner_id', $provider->id)->sum('debt_amount');
            $paidDebt = Debtor::where('owner_id', $provider->id)
                ->where('status', 'paid')
                ->sum('debt_amount');
            $provider->collection_rate = $totalDebt > 0 ? ($paidDebt / $totalDebt) * 100 : 0;
            
            // AI Usage (static حالياً)
            $provider->ai_usage = '0%'; // سيتم تحديثه لاحقاً
        }

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.ai-reports.partials.providers-table', compact('providers'))->render(),
                'pagination' => $providers->links()->render(),
            ]);
        }

        return view('admin.ai-reports.service-providers', compact('providers'));
    }

    /**
     * تقارير الحملات (Campaigns Reports)
     * 
     * يعرض جدول حملات التحصيل مع:
     * - اسم الحملة
     * - المرسل (Service Provider)
     * - قناة التواصل
     * - عدد المديونين
     * - وقت الإرسال
     * - حالة الحملة
     */
    public function campaigns(Request $request)
    {
        $query = CollectionCampaign::with(['owner', 'clients']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب القناة
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }

        // فلترة حسب المرسل
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        $campaigns = $query->latest()->paginate(10)->appends($request->query());
        
        // جلب قائمة المالكين للفلترة
        $owners = User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->get();

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.ai-reports.partials.campaigns-table', compact('campaigns'))->render(),
                'pagination' => $campaigns->links()->render(),
            ]);
        }

        return view('admin.ai-reports.campaigns', compact('campaigns', 'owners'));
    }

    /**
     * تقارير الرسائل (Messages Reports)
     * 
     * يعرض جدول شامل لجميع الرسائل المرسلة من خلال الحملات
     */
    public function messages(Request $request)
    {
        // جلب جميع الرسائل من خلال pivot table collection_campaign_clients
        $query = DB::table('collection_campaign_clients')
            ->join('collection_campaigns', 'collection_campaign_clients.campaign_id', '=', 'collection_campaigns.id')
            ->join('clients', 'collection_campaign_clients.client_id', '=', 'clients.id')
            ->join('users', 'collection_campaigns.owner_id', '=', 'users.id')
            ->select(
                'collection_campaign_clients.*',
                'collection_campaigns.id as campaign_id',
                'collection_campaigns.campaign_number',
                'collection_campaigns.channel',
                'collection_campaigns.message as message_content',
                'collection_campaigns.created_at as campaign_created_at',
                'clients.name as client_name',
                'clients.phone as client_phone',
                'clients.email as client_email',
                'users.name as provider_name',
                'users.email as provider_email'
            );

        // البحث
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('clients.name', 'like', "%{$search}%")
                  ->orWhere('clients.phone', 'like', "%{$search}%")
                  ->orWhere('collection_campaigns.message_content', 'like', "%{$search}%");
            });
        }

        // فلترة حسب القناة
        if ($request->filled('channel')) {
            $query->where('collection_campaigns.channel', $request->channel);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('collection_campaign_clients.status', $request->status);
        }

        $messages = $query->orderBy('collection_campaign_clients.created_at', 'desc')
            ->paginate(10)->appends($request->query());

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.ai-reports.partials.messages-table', compact('messages'))->render(),
                'pagination' => $messages->links()->render(),
            ]);
        }

        // تجميع الرسائل حسب الحملة لعرض المستلمين (للصفحة العادية فقط)
        $groupedMessages = [];
        foreach ($messages as $message) {
            $campaignId = $message->campaign_id;
            if (!isset($groupedMessages[$campaignId])) {
                $groupedMessages[$campaignId] = [
                    'campaign' => $message,
                    'recipients' => []
                ];
            }
            $groupedMessages[$campaignId]['recipients'][] = $message;
        }

        return view('admin.ai-reports.messages', compact('messages', 'groupedMessages'));
    }

    /**
     * تقارير الاشتراكات (Subscriptions Reports)
     * 
     * يعرض إحصائيات الاشتراكات:
     * - إجمالي الاشتراكات النشطة
     * - أكثر باقة استخدامًا
     * - معدل التجديد
     * - عدد الاشتراكات المرفوضة
     */
    public function subscriptions(Request $request)
    {
        // إجمالي الاشتراكات النشطة
        $activeSubscriptions = UserSubscription::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->count();

        // أكثر باقة استخدامًا
        $mostUsedSubscription = Subscription::withCount('userSubscriptions')
            ->orderBy('user_subscriptions_count', 'desc')
            ->first();

        // معدل التجديد (حساب بسيط)
        $totalSubscriptions = UserSubscription::count();
        $renewedSubscriptions = UserSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->count();
        $renewalRate = $totalSubscriptions > 0 ? ($renewedSubscriptions / $totalSubscriptions) * 100 : 0;

        // عدد الاشتراكات المرفوضة
        $rejectedRequests = SubscriptionRequest::where('status', 'rejected')->count();

        // فلترة حسب الباقة
        $query = UserSubscription::with(['user', 'subscription']);
        if ($request->filled('subscription_id')) {
            $query->where('subscription_id', $request->subscription_id);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الفترة الزمنية
        if ($request->filled('date_from')) {
            $query->where('started_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('started_at', '<=', $request->date_to);
        }

        $subscriptions = $query->latest('started_at')->paginate(10)->appends($request->query());
        
        // جلب جميع الباقات للفلترة
        $allSubscriptions = Subscription::all();

        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.ai-reports.partials.subscriptions-table', compact('subscriptions'))->render(),
                'pagination' => $subscriptions->links()->render(),
            ]);
        }

        return view('admin.ai-reports.subscriptions', compact(
            'activeSubscriptions',
            'mostUsedSubscription',
            'renewalRate',
            'rejectedRequests',
            'subscriptions',
            'allSubscriptions'
        ));
    }
}

