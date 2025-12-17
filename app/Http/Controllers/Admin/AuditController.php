<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\CollectionCampaign;
use App\Models\User;
use App\Models\Ticket;
use App\Models\UserSubscription;
use App\Models\SubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Audit Controller for Admin
 * 
 * يعرض سجلات التدقيق والمراجعة لجميع المستخدمين
 */
class AuditController extends Controller
{
    /**
     * عرض صفحة Audit & Logs
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // جمع الأنشطة من مصادر مختلفة لجميع المستخدمين
        $activities = [];
        
        // 1. إضافة المديونين (من جميع المالكين)
        $debtorsQuery = Debtor::with('owner');
        
        // فلترة حسب المستخدم
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $debtorsQuery->where('owner_id', $request->user_id);
        }
        
        // فلترة حسب الفترة الزمنية
        if ($request->has('date_from') && $request->date_from) {
            $debtorsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $debtorsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $recentDebtors = $debtorsQuery->latest()
            ->limit(100)
            ->get()
            ->map(function($debtor) {
                return [
                    'type' => 'add_debtor',
                    'type_text' => 'إضافة مديون',
                    'user_name' => $debtor->owner->name,
                    'user_id' => $debtor->owner_id,
                    'related_name' => $debtor->name,
                    'related_id' => $debtor->id,
                    'related_type' => 'debtor',
                    'status' => 'success',
                    'created_at' => $debtor->created_at,
                ];
            });
        $activities = array_merge($activities, $recentDebtors->toArray());
        
        // 2. إنشاء الحملات
        $campaignsQuery = CollectionCampaign::with('owner');
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $campaignsQuery->where('owner_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $campaignsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $campaignsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $recentCampaigns = $campaignsQuery->latest()
            ->limit(100)
            ->get()
            ->map(function($campaign) {
                return [
                    'type' => 'create_campaign',
                    'type_text' => 'إنشاء حملة',
                    'user_name' => $campaign->owner->name,
                    'user_id' => $campaign->owner_id,
                    'related_name' => $campaign->campaign_number,
                    'related_id' => $campaign->id,
                    'related_type' => 'campaign',
                    'status' => 'success',
                    'created_at' => $campaign->created_at,
                ];
            });
        $activities = array_merge($activities, $recentCampaigns->toArray());
        
        // 3. تغييرات حالة الدين (من notifications)
        $statusChangesQuery = DB::table('notifications')
            ->where('type', 'App\Notifications\DebtorStatusChangedNotification')
            ->whereBetween('created_at', [
                $request->get('date_from', '2000-01-01'),
                $request->get('date_to', now()->format('Y-m-d')) . ' 23:59:59'
            ]);
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $statusChangesQuery->where('notifiable_id', $request->user_id);
        }
        
        $statusChanges = $statusChangesQuery->latest('created_at')
            ->limit(100)
            ->get()
            ->map(function($notification) {
                $data = json_decode($notification->data, true);
                $user = User::find($notification->notifiable_id);
                
                return [
                    'type' => 'change_status',
                    'type_text' => 'تغيير حالة دين',
                    'user_name' => $user ? $user->name : 'غير معروف',
                    'user_id' => $notification->notifiable_id,
                    'related_name' => $data['debtor_name'] ?? '-',
                    'related_id' => $data['debtor_id'] ?? null,
                    'related_type' => 'debtor',
                    'status' => 'success',
                    'created_at' => $notification->created_at,
                ];
            });
        $activities = array_merge($activities, $statusChanges->toArray());
        
        // 4. إنشاء الشكاوى
        $ticketsQuery = Ticket::with('user');
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $ticketsQuery->where('user_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $ticketsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $ticketsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $recentTickets = $ticketsQuery->latest()
            ->limit(100)
            ->get()
            ->map(function($ticket) {
                return [
                    'type' => 'create_ticket',
                    'type_text' => 'إنشاء شكوى',
                    'user_name' => $ticket->user->name,
                    'user_id' => $ticket->user_id,
                    'related_name' => $ticket->ticket_number,
                    'related_id' => $ticket->id,
                    'related_type' => 'ticket',
                    'status' => 'success',
                    'created_at' => $ticket->created_at,
                ];
            });
        $activities = array_merge($activities, $recentTickets->toArray());
        
        // 5. طلبات الاشتراك
        $subscriptionRequestsQuery = SubscriptionRequest::with(['user', 'subscription']);
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $subscriptionRequestsQuery->where('user_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $subscriptionRequestsQuery->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $subscriptionRequestsQuery->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }
        
        $recentRequests = $subscriptionRequestsQuery->latest()
            ->limit(100)
            ->get()
            ->map(function($req) {
                return [
                    'type' => 'subscription_request',
                    'type_text' => 'طلب اشتراك',
                    'user_name' => $req->user->name,
                    'user_id' => $req->user_id,
                    'related_name' => $req->subscription ? $req->subscription->name : '-',
                    'related_id' => $req->id,
                    'related_type' => 'subscription_request',
                    'status' => $req->status,
                    'created_at' => $req->created_at,
                ];
            });
        $activities = array_merge($activities, $recentRequests->toArray());
        
        // 6. إرسال الرسائل (من notifications)
        $messageSentQuery = DB::table('notifications')
            ->where('type', 'App\Notifications\MessageSentNotification')
            ->whereBetween('created_at', [
                $request->get('date_from', '2000-01-01'),
                $request->get('date_to', now()->format('Y-m-d')) . ' 23:59:59'
            ]);
        
        if ($request->has('user_id') && $request->user_id !== 'all') {
            $messageSentQuery->where('notifiable_id', $request->user_id);
        }
        
        $messageSent = $messageSentQuery->latest('created_at')
            ->limit(100)
            ->get()
            ->map(function($notification) {
                $data = json_decode($notification->data, true);
                $user = User::find($notification->notifiable_id);
                
                return [
                    'type' => 'send_message',
                    'type_text' => 'إرسال رسالة',
                    'user_name' => $user ? $user->name : 'غير معروف',
                    'user_id' => $notification->notifiable_id,
                    'related_name' => $data['campaign_number'] ?? '-',
                    'related_id' => $data['campaign_id'] ?? null,
                    'related_type' => 'campaign',
                    'status' => 'success',
                    'created_at' => $notification->created_at,
                ];
            });
        $activities = array_merge($activities, $messageSent->toArray());
        
        // فلترة حسب نوع العملية
        if ($request->has('action_type') && $request->action_type !== 'all') {
            $activities = array_filter($activities, function($activity) use ($request) {
                return $activity['type'] === $request->action_type;
            });
        }
        
        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== 'all') {
            $activities = array_filter($activities, function($activity) use ($request) {
                return $activity['status'] === $request->status;
            });
        }
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $activities = array_filter($activities, function($activity) use ($search) {
                return stripos($activity['user_name'], $search) !== false ||
                       stripos($activity['related_name'], $search) !== false ||
                       stripos($activity['type_text'], $search) !== false;
            });
        }
        
        // ترتيب حسب التاريخ (الأحدث أولاً)
        usort($activities, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        // Pagination يدوي
        $page = $request->get('page', 1);
        $perPage = 10;
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
        
        // جلب قائمة المستخدمين للفلترة
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'owner');
        })->orderBy('name')->get(['id', 'name']);
        
        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.audit.partials.audit-table', compact('activities'))->render(),
                'pagination' => $activities->appends($request->query())->links()->render(),
            ]);
        }
        
        return view('admin.audit.index', compact('activities', 'users'));
    }
}
