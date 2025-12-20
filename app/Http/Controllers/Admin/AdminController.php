<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * Admin Controller for Managing Admins
 * 
 * يدير إضافة وتعديل وحذف المشرفين
 */
class AdminController extends Controller
{
    /**
     * عرض قائمة جميع المشرفين
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('view admins')) {
            abort(403, 'غير مصرح لك بعرض المشرفين.');
        }

        // جلب جميع المستخدمين الذين لديهم دور admin أو super_admin أو أي دور مخصص
        $query = User::whereHas('roles', function($q) {
                $q->where('name', '!=', 'owner');
            })
            ->with('roles');
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب الدور
        if ($request->has('role') && $request->role !== 'all') {
            $query->role($request->role);
        }
        
        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $admins = $query->paginate(10);
        
        // جلب الأدوار المتاحة
        $roles = Role::where('name', '!=', 'owner')->orderBy('name', 'asc')->get();
        
        // إذا كان الطلب AJAX
        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.admins.partials.admins-table', compact('admins'))->render(),
                'pagination' => $admins->appends($request->query())->links()->render(),
            ]);
        }
        
        return view('admin.admins.index', compact('admins', 'roles'));
    }

    /**
     * عرض صفحة إنشاء مشرف جديد
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create admins')) {
            abort(403, 'غير مصرح لك بإنشاء مشرف جديد.');
        }

        // جلب جميع الأدوار (بما في ذلك الأدوار المخصصة)
        $roles = Role::where('name', '!=', 'owner')->orderBy('name', 'asc')->get();
        
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * حفظ مشرف جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create admins')) {
            abort(403, 'غير مصرح لك بإنشاء مشرف جديد.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        // إنشاء المستخدم
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // تعيين الدور (الصلاحيات ستأتي تلقائياً من الدور)
        $admin->assignRole($validated['role']);

        // إرسال إشعار
        try {
            Auth::user()->notify(new \App\Notifications\AdminCreatedNotification($admin));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin created notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم إنشاء المشرف بنجاح.');
    }

    /**
     * عرض صفحة تعديل مشرف
     * 
     * @param User $admin
     * @return \Illuminate\View\View
     */
    public function edit(User $admin)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit admins')) {
            abort(403, 'غير مصرح لك بتعديل المشرفين.');
        }

        // التحقق من أن المستخدم مشرف (ليس owner)
        if ($admin->hasRole('owner')) {
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin من التعديل من قبل غير Super Admin (Super Admin يمكنه تعديل نفسه والآخرين)
        if ($admin->hasRole('super_admin') && !$user->hasRole('super_admin')) {
            abort(403, 'لا يمكنك تعديل Super Admin.');
        }

        // جلب جميع الأدوار (بما في ذلك الأدوار المخصصة)
        $roles = Role::where('name', '!=', 'owner')->orderBy('name', 'asc')->get();
        
        $admin->load('roles');
        
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * تحديث بيانات مشرف
     * 
     * @param Request $request
     * @param User $admin
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $admin)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit admins')) {
            abort(403, 'غير مصرح لك بتعديل المشرفين.');
        }

        // التحقق من أن المستخدم مشرف (ليس owner)
        if ($admin->hasRole('owner')) {
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin (Super Admin يمكنه تعديل نفسه والآخرين)
        if ($admin->hasRole('super_admin') && !$user->hasRole('super_admin')) {
            abort(403, 'لا يمكنك تعديل Super Admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        // تحديث البيانات الأساسية
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'] ?? $admin->is_active,
        ]);

        // تحديث كلمة المرور إن وجدت
        if (!empty($validated['password'])) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }

        // تحديث الدور (الصلاحيات ستأتي تلقائياً من الدور)
        $admin->syncRoles([$validated['role']]);

        // إرسال إشعار
        try {
            Auth::user()->notify(new \App\Notifications\AdminUpdatedNotification($admin));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin updated notification: ' . $e->getMessage());
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم تحديث بيانات المشرف بنجاح.');
    }

    /**
     * حذف مشرف
     * 
     * @param Request $request
     * @param User $admin
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, User $admin)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('delete admins')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بحذف المشرفين.'
                ], 403);
            }
            abort(403, 'غير مصرح لك بحذف المشرفين.');
        }

        // التحقق من أن المستخدم مشرف
        if (!$admin->hasAnyRole(['admin', 'super_admin'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم المحدد ليس مشرفاً.'
                ], 404);
            }
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin من الحذف (Super Admin يمكنه حذف الآخرين لكن لا يمكنه حذف نفسه)
        if ($admin->hasRole('super_admin') && !$user->hasRole('super_admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف Super Admin.'
                ], 403);
            }
            abort(403, 'لا يمكن حذف Super Admin.');
        }

        // منع حذف نفسه
        if ($admin->id === Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك حذف حسابك الخاص.'
                ], 403);
            }
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $admin->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المشرف بنجاح.'
            ]);
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'تم حذف المشرف بنجاح.');
    }

    /**
     * تفعيل/إيقاف حساب مشرف
     * 
     * @param Request $request
     * @param User $admin
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function toggleStatus(Request $request, User $admin)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit admins')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بتعديل المشرفين.'
                ], 403);
            }
            abort(403, 'غير مصرح لك بتعديل المشرفين.');
        }

        // التحقق من أن المستخدم مشرف
        if (!$admin->hasAnyRole(['admin', 'super_admin'])) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستخدم المحدد ليس مشرفاً.'
                ], 404);
            }
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin (Super Admin يمكنه تعطيل الآخرين لكن لا يمكنه تعطيل نفسه)
        if ($admin->hasRole('super_admin') && !$user->hasRole('super_admin')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك تعطيل Super Admin.'
                ], 403);
            }
            abort(403, 'لا يمكنك تعطيل Super Admin.');
        }

        // منع تعطيل نفسه
        if ($admin->id === Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكنك تعطيل حسابك الخاص.'
                ], 403);
            }
            return back()->with('error', 'لا يمكنك تعطيل حسابك الخاص.');
        }

        $admin->update(['is_active' => !$admin->is_active]);

        $status = $admin->is_active ? 'تفعيل' : 'إيقاف';
        
        // إرسال إشعار
        try {
            Auth::user()->notify(new \App\Notifications\AdminStatusChangedNotification($admin, $admin->is_active));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin status notification: ' . $e->getMessage());
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "تم {$status} حساب المشرف بنجاح."
            ]);
        }

        return back()->with('success', "تم {$status} حساب المشرف بنجاح.");
    }
}
