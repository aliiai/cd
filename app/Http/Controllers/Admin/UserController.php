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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المستخدمين (باستثناء Admin)
        $users = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })
            ->with('roles')
            ->latest()
            ->get();
        
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
            'collectionRate',
            'recentClients',
            'recentPaidClients'
        ));
    }

    /**
     * تفعيل/إيقاف حساب مستخدم
     * 
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Request $request, User $user)
    {
        // التأكد من أن المستخدم ليس Admin
        if ($user->hasRole('admin')) {
            return back()->with('error', 'لا يمكن تعديل حالة حساب Admin');
        }

        // تبديل الحالة
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'مفعل' : 'موقوف';
        
        return back()->with('success', "تم {$status} حساب المستخدم بنجاح.");
    }
}

