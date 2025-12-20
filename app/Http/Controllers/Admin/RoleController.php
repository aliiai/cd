<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

/**
 * Role Controller for Admin
 * 
 * يدير عرض وإدارة الأدوار والصلاحيات
 */
class RoleController extends Controller
{
    /**
     * عرض قائمة جميع الأدوار
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('view roles') && !$user->can('view permissions')) {
            abort(403, 'غير مصرح لك بعرض الأدوار.');
        }

        $query = Role::query();
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        // الترتيب
        $roles = $query->with('permissions')->orderBy('name', 'asc')->paginate(20);
        
        // جلب جميع الصلاحيات
        $allPermissions = Permission::all()->groupBy(function($permission) {
            // تجميع الصلاحيات حسب الفئة
            $parts = explode(' ', $permission->name);
            return $parts[0]; // أول كلمة
        });
        
        return view('admin.roles.index', compact('roles', 'allPermissions'));
    }

    /**
     * عرض صفحة إنشاء دور جديد
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create roles') && !$user->can('create permissions')) {
            abort(403, 'غير مصرح لك بإنشاء دور جديد.');
        }

        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * حفظ دور جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('create roles') && !$user->can('create permissions')) {
            abort(403, 'غير مصرح لك بإنشاء دور جديد.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'اسم الدور مطلوب.',
            'name.unique' => 'هذا الدور موجود بالفعل.',
            'permissions.*.exists' => 'إحدى الصلاحيات المحددة غير موجودة.',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء الدور
            $role = Role::create(['name' => $validated['name']]);
            
            // إعطاء الصلاحيات للدور
            if (isset($validated['permissions']) && is_array($validated['permissions']) && count($validated['permissions']) > 0) {
                // تنظيف الصلاحيات من القيم الفارغة
                $permissions = array_filter($validated['permissions'], function($permission) {
                    return !empty($permission);
                });
                
                if (count($permissions) > 0) {
                    $role->givePermissionTo($permissions);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'تم إنشاء الدور بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating role: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء الدور: ' . $e->getMessage());
        }
    }

    /**
     * عرض صفحة تعديل دور
     * 
     * @param Role $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit roles') && !$user->can('edit permissions')) {
            abort(403, 'غير مصرح لك بتعديل الأدوار.');
        }

        // منع تعديل الأدوار الأساسية (Super Admin يمكنه تعديلها)
        if (!$user->hasRole('super_admin') && in_array($role->name, ['admin', 'owner', 'super_admin'])) {
            abort(403, 'لا يمكن تعديل الأدوار الأساسية.');
        }

        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0];
        });
        
        $role->load('permissions');
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * تحديث دور
     * 
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('edit roles') && !$user->can('edit permissions')) {
            abort(403, 'غير مصرح لك بتعديل الأدوار.');
        }

        // منع تعديل الأدوار الأساسية (Super Admin يمكنه تعديلها)
        if (!$user->hasRole('super_admin') && in_array($role->name, ['admin', 'owner', 'super_admin'])) {
            abort(403, 'لا يمكن تعديل الأدوار الأساسية.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        DB::beginTransaction();
        try {
            $role->update(['name' => $validated['name']]);
            
            if (isset($validated['permissions'])) {
                $role->syncPermissions($validated['permissions']);
            } else {
                $role->permissions()->detach();
            }
            
            DB::commit();
            
            return redirect()->route('admin.roles.index')
                ->with('success', 'تم تحديث الدور بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الدور: ' . $e->getMessage());
        }
    }

    /**
     * حذف دور
     * 
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && !$user->can('delete roles') && !$user->can('delete permissions')) {
            abort(403, 'غير مصرح لك بحذف الأدوار.');
        }

        // منع حذف الأدوار الأساسية (Super Admin يمكنه حذفها)
        if (!$user->hasRole('super_admin') && in_array($role->name, ['admin', 'owner', 'super_admin'])) {
            return back()->with('error', 'لا يمكن حذف الأدوار الأساسية.');
        }

        // التحقق من وجود مستخدمين مرتبطين بهذا الدور
        $usersCount = DB::table('model_has_roles')
            ->where('role_id', $role->id)
            ->count();

        if ($usersCount > 0) {
            return back()->with('error', 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين. يرجى إزالة المستخدمين من هذا الدور أولاً.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح.');
    }
}

