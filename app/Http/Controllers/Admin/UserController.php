<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Debtor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * User Controller for Admin
 * 
 * هذا Controller يدير صفحة Users في لوحة تحكم Admin
 */
class UserController extends Controller
{
    /**
     * عرض قائمة جميع المستخدمين
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // جلب جميع المستخدمين (باستثناء Admin)
        $query = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->with('roles', 'activeSubscription');
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب حالة الحساب
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // فلترة حسب الدور
        if ($request->has('role') && $request->role !== 'all') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        // Pagination
        $users = $query->paginate(10);
        
        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.users.partials.users-table', compact('users'))->render(),
                'pagination' => $users->appends($request->query())->links()->render(),
            ]);
        }
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * عرض تفاصيل مستخدم معين
     * 
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // التأكد من أن المستخدم ليس Admin
        if ($user->hasRole('admin')) {
            abort(403, 'لا يمكن عرض تفاصيل Admin');
        }

        // جلب الاشتراك النشط
        $activeSubscription = $user->getActiveSubscription();
        
        // إحصائيات المديونين
        $totalDebtors = Debtor::where('owner_id', $user->id)->count();
        $paidDebtors = Debtor::where('owner_id', $user->id)->where('status', 'paid')->count();
        $overdueDebtors = Debtor::where('owner_id', $user->id)->where('status', 'overdue')->count();
        $totalDebtAmount = Debtor::where('owner_id', $user->id)->sum('debt_amount');
        $paidAmount = Debtor::where('owner_id', $user->id)->where('status', 'paid')->sum('debt_amount');
        $collectionRate = $totalDebtAmount > 0 ? ($paidAmount / $totalDebtAmount) * 100 : 0;

        // آخر المديونين
        $recentDebtors = Debtor::where('owner_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // آخر المديونين المدفوعين
        $recentPaidDebtors = Debtor::where('owner_id', $user->id)
            ->where('status', 'paid')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'recentDebtors',
            'recentPaidDebtors',
            'activeSubscription',
            'totalDebtors',
            'paidDebtors',
            'overdueDebtors',
            'totalDebtAmount',
            'paidAmount',
            'collectionRate'
        ));
    }

    /**
     * تفعيل/إيقاف حساب مستخدم
     * 
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, User $user)
    {
        // التأكد من أن المستخدم ليس Admin
        if ($user->hasRole('admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تعديل حالة حساب Admin'
                ], 403);
            }
            return back()->with('error', 'لا يمكن تعديل حالة حساب Admin');
        }

        // تبديل الحالة
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'تفعيل' : 'إيقاف';
        $message = "تم {$status} حساب المستخدم بنجاح.";
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
        
        return back()->with('success', $message);
    }
}

