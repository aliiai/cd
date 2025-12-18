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
        // التحقق من الصلاحية
        if (!Auth::user()->can('view admins')) {
            abort(403, 'غير مصرح لك بعرض المشرفين.');
        }

        // جلب جميع المستخدمين الذين لديهم دور admin أو super_admin
        $query = User::role(['admin', 'super_admin'])
            ->with('roles', 'permissions');
        
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
        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();
        
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
        // التحقق من الصلاحية
        if (!Auth::user()->can('create admins')) {
            abort(403, 'غير مصرح لك بإنشاء مشرف جديد.');
        }

        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            // تجميع الصلاحيات حسب الفئة
            $parts = explode(' ', $permission->name);
            return $parts[0]; // أول كلمة (manage, view, create, etc.)
        });
        
        return view('admin.admins.create', compact('roles', 'permissions'));
    }

    /**
     * حفظ مشرف جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحية
        if (!Auth::user()->can('create admins')) {
            abort(403, 'غير مصرح لك بإنشاء مشرف جديد.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,super_admin',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'is_active' => 'boolean',
        ]);

        // إنشاء المستخدم
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // تعيين الدور
        $admin->assignRole($validated['role']);

        // تعيين الصلاحيات
        if (isset($validated['permissions']) && !empty($validated['permissions'])) {
            $admin->givePermissionTo($validated['permissions']);
        }

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
        // التحقق من الصلاحية
        if (!Auth::user()->can('edit admins')) {
            abort(403, 'غير مصرح لك بتعديل المشرفين.');
        }

        // التحقق من أن المستخدم مشرف
        if (!$admin->hasAnyRole(['admin', 'super_admin'])) {
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin من التعديل من قبل غير Super Admin
        if ($admin->hasRole('super_admin') && !Auth::user()->hasRole('super_admin')) {
            abort(403, 'لا يمكنك تعديل Super Admin.');
        }

        $roles = Role::whereIn('name', ['admin', 'super_admin'])->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        
        $admin->load('roles', 'permissions');
        
        return view('admin.admins.edit', compact('admin', 'roles', 'permissions'));
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
        // التحقق من الصلاحية
        if (!Auth::user()->can('edit admins')) {
            abort(403, 'غير مصرح لك بتعديل المشرفين.');
        }

        // التحقق من أن المستخدم مشرف
        if (!$admin->hasAnyRole(['admin', 'super_admin'])) {
            abort(404, 'المستخدم المحدد ليس مشرفاً.');
        }

        // حماية Super Admin
        if ($admin->hasRole('super_admin') && !Auth::user()->hasRole('super_admin')) {
            abort(403, 'لا يمكنك تعديل Super Admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,super_admin',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
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

        // تحديث الدور
        $admin->syncRoles([$validated['role']]);

        // تحديث الصلاحيات
        if (isset($validated['permissions'])) {
            $admin->syncPermissions($validated['permissions']);
        } else {
            $admin->permissions()->detach();
        }

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
        // التحقق من الصلاحية
        if (!Auth::user()->can('delete admins')) {
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

        // حماية Super Admin من الحذف
        if ($admin->hasRole('super_admin')) {
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
        // التحقق من الصلاحية
        if (!Auth::user()->can('edit admins')) {
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

        // حماية Super Admin
        if ($admin->hasRole('super_admin') && !Auth::user()->hasRole('super_admin')) {
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
