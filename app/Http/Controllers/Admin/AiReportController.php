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
use App\Services\BrowsershotPdfService;
use App\Services\MpdfService;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * تصدير تقرير مقدمي الخدمة إلى PDF
     */
    public function exportServiceProviders(Request $request)
    {
        $query = User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->with(['activeSubscription.subscription']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $providers = $query->get();

        // حساب الإحصائيات
        foreach ($providers as $provider) {
            $provider->debtors_count = Debtor::where('owner_id', $provider->id)->count();
            $provider->messages_sent = CollectionCampaign::where('owner_id', $provider->id)
                ->where('status', 'sent')
                ->sum('sent_count');
            $totalDebt = Debtor::where('owner_id', $provider->id)->sum('debt_amount');
            $paidDebt = Debtor::where('owner_id', $provider->id)
                ->where('status', 'paid')
                ->sum('debt_amount');
            $provider->collection_rate = $totalDebt > 0 ? ($paidDebt / $totalDebt) * 100 : 0;
        }

        $html = view('admin.ai-reports.exports.service-providers-pdf', [
            'providers' => $providers,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        try {
            $bytes = app(BrowsershotPdfService::class)->htmlToPdfBytes($html, [
                'format' => 'A4',
                'orientation' => 'landscape',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Browsershot PDF generation failed, falling back to mPDF', [
                'error' => $e->getMessage(),
            ]);
            try {
                $bytes = app(MpdfService::class)->htmlToPdfBytes($html, [
                    'format' => 'A4',
                    'orientation' => 'L',
                ]);
            } catch (\Exception $e2) {
                \Log::warning('mPDF generation failed, falling back to DomPDF', [
                    'error' => $e2->getMessage(),
                ]);
                $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
                $bytes = $pdf->output();
            }
        }

        $filename = 'تقرير_مقدمي_الخدمة_' . now()->format('Y-m-d') . '.pdf';

        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode($filename),
        ]);
    }

    /**
     * تصدير تقرير الحملات إلى PDF
     */
    public function exportCampaigns(Request $request)
    {
        $query = CollectionCampaign::with(['owner', 'clients']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        $campaigns = $query->latest()->get();

        $totalCampaigns = $campaigns->count();
        $smsCount = $campaigns->where('channel', 'sms')->count();
        $emailCount = $campaigns->where('channel', 'email')->count();
        $sentCount = $campaigns->where('status', 'sent')->count();

        $html = view('admin.ai-reports.exports.campaigns-pdf', [
            'campaigns' => $campaigns,
            'totalCampaigns' => $totalCampaigns,
            'smsCount' => $smsCount,
            'emailCount' => $emailCount,
            'sentCount' => $sentCount,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        try {
            $bytes = app(BrowsershotPdfService::class)->htmlToPdfBytes($html, [
                'format' => 'A4',
                'orientation' => 'landscape',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Browsershot PDF generation failed, falling back to mPDF', [
                'error' => $e->getMessage(),
            ]);
            try {
                $bytes = app(MpdfService::class)->htmlToPdfBytes($html, [
                    'format' => 'A4',
                    'orientation' => 'L',
                ]);
            } catch (\Exception $e2) {
                \Log::warning('mPDF generation failed, falling back to DomPDF', [
                    'error' => $e2->getMessage(),
                ]);
                $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
                $bytes = $pdf->output();
            }
        }

        $filename = 'تقرير_الحملات_' . now()->format('Y-m-d') . '.pdf';

        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode($filename),
        ]);
    }

    /**
     * تصدير تقرير الرسائل إلى PDF
     */
    public function exportMessages(Request $request)
    {
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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('clients.name', 'like', "%{$search}%")
                  ->orWhere('clients.phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('channel')) {
            $query->where('collection_campaigns.channel', $request->channel);
        }

        if ($request->filled('status')) {
            $query->where('collection_campaign_clients.status', $request->status);
        }

        $messages = $query->orderBy('collection_campaign_clients.created_at', 'desc')->get();

        $totalMessages = $messages->count();
        $smsCount = $messages->where('channel', 'sms')->count();
        $emailCount = $messages->where('channel', 'email')->count();
        $successCount = $messages->where('status', 'sent')->count();
        $failedCount = $messages->where('status', 'failed')->count();
        $successRate = $totalMessages > 0 ? ($successCount / $totalMessages) * 100 : 0;
        $failedRate = $totalMessages > 0 ? ($failedCount / $totalMessages) * 100 : 0;

        $html = view('admin.ai-reports.exports.messages-pdf', [
            'messages' => $messages,
            'totalMessages' => $totalMessages,
            'smsCount' => $smsCount,
            'emailCount' => $emailCount,
            'successCount' => $successCount,
            'failedCount' => $failedCount,
            'successRate' => $successRate,
            'failedRate' => $failedRate,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        try {
            $bytes = app(BrowsershotPdfService::class)->htmlToPdfBytes($html, [
                'format' => 'A4',
                'orientation' => 'landscape',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Browsershot PDF generation failed, falling back to mPDF', [
                'error' => $e->getMessage(),
            ]);
            try {
                $bytes = app(MpdfService::class)->htmlToPdfBytes($html, [
                    'format' => 'A4',
                    'orientation' => 'L',
                ]);
            } catch (\Exception $e2) {
                \Log::warning('mPDF generation failed, falling back to DomPDF', [
                    'error' => $e2->getMessage(),
                ]);
                $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
                $bytes = $pdf->output();
            }
        }

        $filename = 'تقرير_الرسائل_' . now()->format('Y-m-d') . '.pdf';

        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode($filename),
        ]);
    }

    /**
     * تصدير تقرير الاشتراكات إلى PDF
     */
    public function exportSubscriptions(Request $request)
    {
        $activeSubscriptions = UserSubscription::where('status', 'active')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->count();

        $mostUsedSubscription = Subscription::withCount('userSubscriptions')
            ->orderBy('user_subscriptions_count', 'desc')
            ->first();

        $totalSubscriptions = UserSubscription::count();
        $renewedSubscriptions = UserSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->count();
        $renewalRate = $totalSubscriptions > 0 ? ($renewedSubscriptions / $totalSubscriptions) * 100 : 0;

        $rejectedRequests = SubscriptionRequest::where('status', 'rejected')->count();

        $query = UserSubscription::with(['user', 'subscription']);
        if ($request->filled('subscription_id')) {
            $query->where('subscription_id', $request->subscription_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('started_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('started_at', '<=', $request->date_to);
        }

        $subscriptions = $query->latest('started_at')->get();

        $html = view('admin.ai-reports.exports.subscriptions-pdf', [
            'activeSubscriptions' => $activeSubscriptions,
            'mostUsedSubscription' => $mostUsedSubscription,
            'renewalRate' => $renewalRate,
            'rejectedRequests' => $rejectedRequests,
            'subscriptions' => $subscriptions,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ])->render();

        try {
            $bytes = app(BrowsershotPdfService::class)->htmlToPdfBytes($html, [
                'format' => 'A4',
                'orientation' => 'portrait',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Browsershot PDF generation failed, falling back to mPDF', [
                'error' => $e->getMessage(),
            ]);
            try {
                $bytes = app(MpdfService::class)->htmlToPdfBytes($html, [
                    'format' => 'A4',
                    'orientation' => 'P',
                ]);
            } catch (\Exception $e2) {
                \Log::warning('mPDF generation failed, falling back to DomPDF', [
                    'error' => $e2->getMessage(),
                ]);
                $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
                $bytes = $pdf->output();
            }
        }

        $filename = 'تقرير_الاشتراكات_' . now()->format('Y-m-d') . '.pdf';

        return response($bytes, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename*=UTF-8''" . rawurlencode($filename),
        ]);
    }
}

